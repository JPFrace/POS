export interface Config {
    uuid: string;
    name: string;
    slug: string;
    type: string;
    options?: any[];
    value: any;
    use_prefix?: number | boolean;
    prefix?: string | null;
    use_suffix?: number | boolean;
    suffix?: string | null;
    children?: Config[];
}