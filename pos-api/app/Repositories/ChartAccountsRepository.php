<?php
namespace App\Repositories;

use App\Enums\AccountUsageType;
use App\Models\ChartAccount;
use App\Models\Product;
use Illuminate\Http\Exceptions\HttpResponseException;

class ChartAccountsRepository extends Repository
{
    use Conditions\ChartAccountConditions;

    public function __construct(protected ChartAccount $model)
    {

    }

    public function create(array $data): ChartAccount
    {
        return \DB::transaction(function () use ($data) {
            $addAsProduct = $data['add_as_product'];
            unset($data['add_as_produt']);

            $chartAccount = parent::create($data);

            if ($addAsProduct && $chartAccount->usageType) { // Check if null
                $usageType = $chartAccount->usageType->name;
                if (
                    $usageType == AccountUsageType::AR->value
                    || $usageType == AccountUsageType::ACCOUNTS_PAYABLE->value
                    || $usageType == AccountUsageType::CASH_IN_BANK->value
                    || $usageType == AccountUsageType::DEPOSITORY->value
                ) {
                    Product::create([
                        'sku' => $chartAccount->code,
                        'name' => $chartAccount->name,
                        'price' => 0,
                        'income_id' => $chartAccount->id
                    ]);
                }
            }

            return $chartAccount;
        });
    }
}
