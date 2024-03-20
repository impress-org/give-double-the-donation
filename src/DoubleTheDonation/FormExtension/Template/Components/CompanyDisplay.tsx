import type {Company} from '../types';

export default ({company, onChange}: ({company: Company, onChange: Function})) => {
    return (
        <div>
            Selected company:

            <strong>
                {company.company_name}
            </strong>

            <a href="#" onClick={() => onChange()}>
                Remove
            </a>
        </div>
    );
}
