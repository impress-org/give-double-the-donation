import {useEffect, useState} from 'react';
import './styles.scss';

declare const window: {
    GiveDTD: {
        apiKey: string;
        endpoint: string;
    };
} & Window;

type Company = {
    id: string;
    company_name: string;
    matching_gift_offered: boolean;
    minimum_matched_amount?: number;
    maximum_matched_amount?: number;
}

const initialState = {
    text: '',
    companies: [],
};

/**
 * @unreleased
 */
export default ({inputProps: {name, ref}}) => {

    // @ts-ignore
    const {useFormContext, useWatch} = window.givewp.form.hooks;
    const {setValue} = useFormContext();
    const [data, setData] = useState(initialState);

    const donationAmount = useWatch({name: 'amount'});

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

    const handleChange = e => {
        setData({
            companies: [],
            text: e.target.value,
        });
    };

    const handleCompanySelect = ({company_id, company_name}) => {
        setValue(name, {
            company_id,
            company_name,
            entered_text: data.text,
        });
    };

    console.log(data.companies)


    return (
        <div>
            <input
                name={name}
                ref={ref}
                value={data.text}
                type="text"
                onChange={handleChange}
            />
        </div>
    );
};
