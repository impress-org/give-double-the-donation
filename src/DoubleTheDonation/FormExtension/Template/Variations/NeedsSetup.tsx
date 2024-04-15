import {__, sprintf} from '@wordpress/i18n';
import {Interweave}  from 'interweave';
import './styles.scss';

declare const window: {
    GiveDTD: {
        settingsPage: boolean;
    };
} & Window;

/**
 * @unreleased
 */
export default () => {
    return (
        <div className="give-dtd-block-template">
            <Interweave
                content={sprintf(
                    __('This block requires additional setup. Go to %s to connect your account.', 'give-double-the-donation'),
                    `<a href="${window.GiveDTD.settingsPage}" target="_parent">${__('settings', 'give-double-the-donation')}</a>`
                )}
            />
        </div>
    );
}
