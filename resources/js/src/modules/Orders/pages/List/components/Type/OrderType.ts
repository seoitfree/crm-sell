
export interface OrderType {
    id: string;
    manager: string;
    order_date: string;
    order_number: number;
    vendor_code: string;
    goods_name: string;
    manager_comment: string;
    sell_price: number;
    status: string;
    status_alias: string;
    amount_in_order_paid: string;
    cost: number;
    shipments_amount: number;
    remainder: number;
    provider_start: string;
    provider_start_id: string;
    date_check: string;
    comment: string;
    defect: string;
    defect_alias: string;
    comfy_code: string;
    comfy_goods_name: string;
    comfy_brand: string;
    comfy_category: string;
    comfy_price: number;
}