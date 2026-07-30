import SelectDropdown from 'flarum/common/components/SelectDropdown';

import type Mithril from 'mithril';

export default class CustomDropdown extends SelectDropdown {
	oncreate(vnode: Mithril.Vnode): void {
		super.oncreate(vnode);

		this.$('.Dropdown-menu').click((e) => {
			e.stopPropagation();
		});

		// Fix positioning of this element (we want it to be fixed), but the transform prop
		// of the parent prevents it.)
		// Here is some info : https://stackoverflow.com/questions/15194313/transform3d-not-working-with-position-fixed-children/15256339#15256339)
		setTimeout(() => $(this.element).closest('.ModalManager .Modal').css('transform', 'none'), 200);
	}

	getButtonContent() {
		const { label } = this.attrs;
		return [<span className="Button-label">{label}</span>];
	}
}
