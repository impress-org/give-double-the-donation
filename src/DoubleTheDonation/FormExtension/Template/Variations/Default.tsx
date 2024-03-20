import type {Company} from '../types';
import {useEffect, useState} from 'react';
import {CompanyDisplay, CompanySearch} from '../Components';
import './styles.scss';

declare const window: {
    GiveDTD: {
        endpoint: string;
    };
} & Window;

const initialState = {
    text: '',
    companies: [],
};

/**
 * @unreleased
 */
export default ({inputProps: {name}}) => {

    // @ts-ignore
    const {useFormContext, useWatch} = window.givewp.form.hooks;
    const {setValue} = useFormContext();
    const [data, setData] = useState(initialState);

    const donationAmount = useWatch({name: 'amount'});
    const selectedCompany: Company = useWatch({name});

    useEffect(() => {
        // Debounce request
        const timeoutId = setTimeout(async () => {

            if (!data.text)
                return;

            const response = await fetch(`${window.GiveDTD.endpoint}/search_by_company_prefix?company_name_prefix=${data.text}`, {
                mode: 'cors',
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                },
            });

            if (response.ok) {
                const companies = await response.json();

                const filteredCompanies = companies.filter((company: Company) => {
                    if (company.matching_gift_offered) {
                        // Check if donation amount is in range
                        if (company.minimum_matched_amount && company.maximum_matched_amount) {
                            return (
                                donationAmount >= company.minimum_matched_amount
                                && donationAmount <= company.maximum_matched_amount
                            );
                        }

                        return true;
                    }

                    return false;
                });

                setData({
                    ...data,
                    companies: filteredCompanies,
                });
            }

        }, 500);

        return () => clearTimeout(timeoutId);

    }, [data.text, donationAmount]);

    const handleChange = (text: string) => {
        setData({
            companies: [],
            text,
        });
    };

    const handleCompanySelect = ({company_id, company_name, entered_text}) => {
        setValue(name, {
            company_id,
            company_name,
            entered_text,
        });
    };

    const handleCompanyDeselect = () => {
        setValue(name, null);
    };


    return selectedCompany
        ? (
            <CompanyDisplay
                company={selectedCompany}
                onChange={handleCompanyDeselect}
            />
        ) : (
            <CompanySearch
                label={'Search company'} // todo: get actual label
                text={data.text}
                companies={data.companies}
                onChange={handleChange}
                onSelect={handleCompanySelect}
            />
        );
};
