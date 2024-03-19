import {__} from '@wordpress/i18n';
import './styles.scss';

/**
 * @unreleased
 */
function Template() {
    return (
        <div className="give-dtd-block-template">
            {__('This block requires additional setup. Go to settings to connect your account.', 'give-double-the-donation')}
        </div>
    );
}

export default Template;

// @ts-ignore
window.givewp.form.templates.fields.dtd = Template;
