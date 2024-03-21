import {__} from '@wordpress/i18n';
import {InspectorControls, useBlockProps} from '@wordpress/block-editor';
import {PanelBody, PanelRow, TextareaControl, TextControl} from '@wordpress/components';
import {NeedsSetup} from '../Template/Variations';

declare const window: {
    GiveDTD: {
        isApiKeyValid: boolean;
        settingsPage: string
    };
} & Window;

export default ({attributes, setAttributes}) => {
    const blockProps = useBlockProps();

    return (
        <>
            <InspectorControls>
                <PanelBody title={__('Field Settings', 'give-double-the-donation')} initialOpen={true}>
                    <PanelRow>
                        {window.GiveDTD.isApiKeyValid ? (
                            <TextareaControl
                                value={attributes.label}
                                onChange={(value) => setAttributes({label: value})}
                                label={__('Label', 'give-double-the-donation')}
                            />
                        ) : (
                            <div className="give-dtd-block-notice">
                                <strong>
                                    {__('Double the Donation requires setup.', 'give-double-the-donation')}
                                </strong>
                                <div className="give-dtd-block-notice-content">
                                    {__('This block requires your settings to be configured in order to use.', 'give-double-the-donation')}
                                </div>
                                <div>
                                    <a href={window.GiveDTD.settingsPage}>{__('Connect your account', 'give-double-the-donation')}</a>
                                </div>
                            </div>
                        )}
                    </PanelRow>
                </PanelBody>
            </InspectorControls>

            <div {...blockProps}>
                {window.GiveDTD.isApiKeyValid ? (
                    <TextControl
                        label={attributes.label}
                        value={null}
                        onChange={null}
                    />
                ) : (
                    <NeedsSetup />
                )}
            </div>
        </>
    );
}
