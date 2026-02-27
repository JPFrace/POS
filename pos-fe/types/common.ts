export interface Action {
    type: "success" | "warning" | "error" | "info";
}

export interface Throwable {
    errors: string[];
    message: string;
}

export interface Method {
    method: "POST" | "DELETE" | "PATCH" | "GET" | "PUT";
}
