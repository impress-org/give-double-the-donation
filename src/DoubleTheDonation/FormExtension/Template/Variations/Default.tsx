import type {Company} from '../types';
import {__} from '@wordpress/i18n';
import {useEffect, useState} from 'react';
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
 * @since 2.0.0
 */
export default ({inputProps: {name}, label}) => {
    // @ts-ignore
    const {useFormContext, useWatch} = window.givewp.form.hooks;
    const {setValue} = useFormContext();
    const [data, setData] = useState(initialState);

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

                setData({
                    ...data,
                    companies,
                });
            }

        }, 500);

        return () => clearTimeout(timeoutId);

    }, [data.text]);

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

    const handleSelect = ({company_id, company_name, entered_text}: Company) => {
        setValue(name, {
            company_id,
            company_name,
            entered_text,
        });
    };

    if (selectedCompany?.company_name) {
        return (
            <div className="give-dtd-company-search">
                <label>
                    {label}
                </label>
                <div style={{display: 'flex', flexDirection: 'row', flexWrap: 'wrap', gap: 10, padding: 5}}>
                    {__('Company selected', 'give-double-the-donation')}: <strong>{selectedCompany.company_name}.</strong>
                    <a
                        href="#"
                        onClick={() => setValue(name, null)}
                    >
                        {__('Select a different company', 'give-double-the-donation')}
                    </a>
                </div>
            </div>
        );
    }

    return (
        <CompanySearch
            selected={selectedCompany?.company_id}
            label={label}
            searchText={data.text}
            companies={data.companies}
            onChange={handleChange}
            onSelect={handleSelect}
        />
    );
};
