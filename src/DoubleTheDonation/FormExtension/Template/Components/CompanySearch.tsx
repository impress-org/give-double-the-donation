import type {CompanyApi} from '../types';
import {ComboboxControl} from '@wordpress/components';
import './styles.scss';

export default ({label, searchText, onSelect, onChange, companies, selected}) => {
    const options = companies.map((company: CompanyApi) => {
        return {
            value: company.id,
            label: company.company_name,
        };
    });

    return (
        <ComboboxControl
            className="give-dtd-company-search"
            label={label}
            value={selected ?? searchText}
            allowReset={false}
            onChange={id => {
                // we need company name also
                const company = companies.find((company: CompanyApi) => company.id === id);

                onSelect({
                    company_id: company.id,
                    company_name: company.company_name,
                    entered_text: searchText,
                });
            }}
            options={options}
            onFilterValueChange={onChange}
            __experimentalRenderItem={({item}) => selected === item.value ? <strong className="selected">{item.label}</strong> : item.label}
        />
    );
}
