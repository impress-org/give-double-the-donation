import {Default, NeedsSetup} from './Variations';

// @ts-ignore
window.givewp.form.templates.fields.dtd = window.GiveDTD.isApiKeyValid ? Default : NeedsSetup;
