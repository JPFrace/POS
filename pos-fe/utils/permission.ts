export const canAccess = (
    user: any,
    menuItemPermissions: string[] | undefined,
) => {
    if (!menuItemPermissions || !user) return false;

    return menuItemPermissions.some((permit) =>
        user?.roles?.some((role: any) => {
            if (!role.role?.permissions) {
                return false;
            }

            return role.role.permissions.some((permission: any) =>
                [
                    permission.action.policy?.parent?.name,
                    permission.action.policy.name,
                    permission.action.name,
                ]
                    .join(".")
                    .includes(permit),
            );
        }),
    );
};
