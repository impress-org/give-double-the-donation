import {__} from '@wordpress/i18n';
import './styles.scss';

/**
 * @unreleased
 */
export default () => {
    return (
        <div className="give-dtd-block-template">
            {__('This block requires additional setup. Go to settings to connect your account.', 'give-double-the-donation')}
        </div>
    );
}
