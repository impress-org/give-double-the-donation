import block from './block.json';

// @ts-ignore
window.givewp.form.blocks.register(block.name, {
    ...block,
    edit: () => {
        return (
            <div>
                DTD Block
            </div>
        )
    },
    save: () => null,
});
