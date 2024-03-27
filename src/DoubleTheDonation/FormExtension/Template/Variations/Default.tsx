import type {Company} from '../types';
import {useEffect, useState} from 'react';
import {__} from '@wordpress/i18n';
import {CompanySearch} from '../Components';
import './styles.scss';

declare const window: {
    GiveDTD: {
        searchEndpoint: string;
    };
} & Window;

const initialState = {
    text: '',
    companies: [],
};

/**
 * @unreleased
 */
export default ({inputProps: {name}, label}) => {
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

            const response = await fetch(`${window.GiveDTD.searchEndpoint}?company_name_prefix=${data.text}`, {
                mode: 'cors',
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                },
            });

            if (response.ok) {
                const companies = await response.json();

                const filteredCompanies = companies.filter((company: Company) => {
                    // Check if the company has set a minimum and maximum donation amount
                    if (company.minimum_matched_amount && company.maximum_matched_amount) {
                        return (
                            donationAmount >= company.minimum_matched_amount
                            && donationAmount <= company.maximum_matched_amount
                        );
                    }

                    return true;
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

        if (!text) {
            return;
        }

        setData({
            companies: [],
            text,
        });

        // Reset selected company
        if (selectedCompany) {
            setValue(name, null);
        }
    };

    const handleSelect = ({id, company_name, entered_text}) => {
        setValue(name, {
            id,
            company_name,
            entered_text,
        });
    };

    return (
        <CompanySearch
            selected={selectedCompany?.id}
            label={label}
            searchText={data.text}
            companies={data.companies}
            onChange={handleChange}
            onSelect={handleSelect}
        />
    );
};
