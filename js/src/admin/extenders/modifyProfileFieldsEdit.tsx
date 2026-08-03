/*
 * This file is part of glowingblue/localizd.
 *
 * Copyright (c) Glowing Blue AG.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

import app from 'flarum/admin/app';
// @ts-expect-error
import { components } from '@fof-masquerade';
import { extend } from 'flarum/common/extend';
import TranslationKey from '../components/TranslationKey';

import type Mithril from 'mithril';
import type ItemList from 'flarum/common/utils/ItemList';

export default function () {
	extend(components.FieldEdit.prototype, 'fieldItems', function (fields: ItemList<Mithril.Children>, field) {
		const fieldId: number = field.id();

		if (!fieldId) return;

		fields.setContent(
			'name',
			<div className="Form-group">
				<label>{app.translator.trans('fof-masquerade.admin.fields.name')}</label>
				<TranslationKey translation={`glowingblue-localizd.dynamic.profile_field.${fieldId}.name`} />
				<br />
				<span className="helpText">{app.translator.trans('fof-masquerade.admin.fields.name-help')}</span>
			</div>
		);

		fields.setContent(
			'description',
			<div className="Form-group">
				<label>{app.translator.trans('fof-masquerade.admin.fields.description')}</label>
				<TranslationKey translation={`glowingblue-localizd.dynamic.profile_field.${fieldId}.description`} />
				<br />
				<span className="helpText">{app.translator.trans('fof-masquerade.admin.fields.description-help')}</span>
			</div>
		);
	});
}
