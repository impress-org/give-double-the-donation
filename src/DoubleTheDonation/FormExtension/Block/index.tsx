import block from './block.json';
import Edit from './Edit';
import Icon from './Icon';

// @ts-ignore
window.givewp.form.blocks.register(block.name, {
    ...block,
    icon: Icon,
    edit: Edit,
    save: () => null,
});
