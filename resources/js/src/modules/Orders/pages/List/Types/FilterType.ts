
export interface FilterType {
    order_date_from: string;
    order_date_to: string;
    vendor_code: string;
    goods_name: string;
    defect: string;
    provider_start: string;
    manager: string;
    status: Array<string>;
    date_check_from: string;
    date_check_to: string;
    comment: string;
    order_number: string;
    remainder: boolean;
}
