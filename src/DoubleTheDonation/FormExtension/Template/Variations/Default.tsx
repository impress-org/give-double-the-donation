import {__} from '@wordpress/i18n';
import './styles.scss';

/**
 * @unreleased
 */
export default () => {
    // @ts-ignore
    const { useWatch } = window.givewp.form.hooks;

    return (
        <div className="give-dtd-block-template">
            Valid Key
        </div>
    );
};
