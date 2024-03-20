import type {Company} from '../types';
import {ComboboxControl} from '@wordpress/components';

export default ({label, text, onSelect, onChange, companies}) => {
    const filtered = companies.map((company: Company) => {
        return {
            value: company.id,
            label: company.company_name,
        };
    });

    return (
        <ComboboxControl
            label={label}
            value={text}
            allowReset={false}
            onChange={companyId => {
                // we need company name also
                const company = companies.find((company: Company) => company.id === companyId);

                onSelect({
                    company_id: companyId,
                    company_name: company.company_name,
                    entered_text: text,
                });
            }}
            options={filtered}
            onFilterValueChange={onChange}
        />
    );
}
