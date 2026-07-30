import app from 'flarum/admin/app';
import EditGroupModal from 'flarum/admin/components/EditGroupModal';
import { extend } from 'flarum/common/extend';
import { slug } from '../../common';
import TranslationKey from '../components/TranslationKey';
import ItemList from 'flarum/common/utils/ItemList';

import type Mithril from 'mithril';

// Make translation calls shorter
const t = app.translator.trans.bind(app.translator);
const dynGroupKeyPrefix = `${slug}.dynamic.groups.`;
const singularSuffix = '.name_singular';
const pluralSuffix = '.name_plural';

export default function modifyEditGroupModal() {
	extend(EditGroupModal.prototype, 'fields', function (items: ItemList<Mithril.Children>) {
		if (this.group.id() === undefined) return;

		items.setContent(
			'name',
			<div className="Form-group">
				<label>{app.translator.trans('core.admin.edit_group.name_label')}</label>
				<div className="EditGroupModal-name-input">
					<label>{t('core.admin.edit_group.singular_placeholder')}</label>
					<TranslationKey translation={dynGroupKeyPrefix + this.group.id() + singularSuffix} />
					<label>{t('core.admin.edit_group.plural_placeholder')}</label>
					<TranslationKey translation={dynGroupKeyPrefix + this.group.id() + pluralSuffix} />
				</div>
			</div>
		);
	});
}
