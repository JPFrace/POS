export interface Form {
    uuid?: string;
    title: string;
    slug: string;
    content: string;
    baseURL: string;
    files: any[];
    post_category?: any;
    post_type?: any;
    tags: any[];
    featured: boolean;
}
