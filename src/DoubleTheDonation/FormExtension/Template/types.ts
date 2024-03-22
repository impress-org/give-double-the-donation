export type Company = {
    company_id: string;
    company_name: string;
    entered_text: string;
}

export type CompanyApi = {
    id: string;
    company_name: string;
    matching_gift_offered: boolean;
    minimum_matched_amount?: number;
    maximum_matched_amount?: number;
}
