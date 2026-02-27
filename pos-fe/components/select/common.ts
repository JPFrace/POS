import type { Option, Methods } from "~/types/form";

export const onRemote = async (
    url: string,
    method: Methods,
    query?: string | string[]
) => {
    const params = {
        method,
    };

    if (method.toLocaleUpperCase() === "GET") {
        params.query = query;
    } else {
        params.body = query;
    }

    return useClient(url, params);

    // Async function
    // Pass the url value and params here
    // Testing examples
    return new Promise((resolve, reject) => {
        const data = Array(10)
            .fill({
                id: 1,
                label: "Label",
                value: "Value",
                extra: "Extra Column",
                columns: ["id", "label", ["extra", "Extra Column"]],
                children: [
                    {
                        id: 1,
                        value: "Value",
                        label: "Label",
                        extra: "Extra",
                    },
                    {
                        id: 2,
                        value: "Value",
                        label: "Label",
                        extra: "Extra",
                    },
                    {
                        id: 3,
                        value: "Value",
                        label: "Label",
                        extra: "Extra",
                    },
                ],
            })
            .map((d, i) => ({
                ...d,
                label: d.label + " " + (i + 1),
                id: i + 1,
                children: d.children.map((c: Option, i: number) => ({
                    ...c,
                    id: Math.random().toString().replace(/\s|./, ""),
                    value: c.value + " " + (i + 1),
                    label: c.label + " " + (i + 1),
                    extra: c.extra + " " + (i + 1),
                })),
            }));

        setTimeout(() => {
            resolve(data);
        }, 1000);
    });
};
