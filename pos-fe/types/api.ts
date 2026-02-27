import type { FetchOptions } from "ofetch";
import type { AsyncDataOptions } from "#app";
import type { UnwrapRef } from "vue";

export interface ApiOptions {
    fetchOptions?:
        | FetchOptions
        | {
              endpoint: string;
              method: "POST" | "GET" | "DELETE" | "PATCH" | "PUT";
              body?: object[] | Array<any> | UnwrapRef<any>;
              query?: object[] | Array<any> | UnwrapRef<any>;
              params?: object[] | Array<any> | UnwrapRef<any>;
          };
    asyncOptions?: AsyncDataOptions<any>;
}
