import block from './block.json';
import Edit from './Edit';

// @ts-ignore
window.givewp.form.blocks.register(block.name, {
    ...block,
    edit: Edit,
    save: () => null,
});
