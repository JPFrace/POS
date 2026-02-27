
export interface ProductCategories {
    uuid: string;
    name: string;
    description: string;
    parent: ProductCategories | null;
    children: ProductCategories[] | null;
}
