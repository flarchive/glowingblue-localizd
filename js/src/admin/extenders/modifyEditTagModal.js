/*
 * This file is part of glowingblue/localizd.
 *
 * Copyright (c) 2022 Glowing Blue AG.
 * Authors: Ian Morland, Rafael Horvat.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

import app from 'flarum/admin/app';
import EditTagModal from 'flarum/tags/components/EditTagModal';
import { extend } from 'flarum/common/extend';
import TranslationKey from '../components/TranslationKey';
import { slug } from '../../common';
import result from 'lodash.result';

// Make translation calls shorter
const t = app.translator.trans.bind(app.translator);
const tagsSlug = 'flarum-tags';
const tagsPrfx = `${tagsSlug}.admin.edit_tag`;
const dynTagKeyPrefix = `${slug}.dynamic.tags.`;
const labelSuffix = '.name';
const descriptionSuffix = '.description';

export default function () {
	if (app.initializers.has(tagsSlug)) {
		extend(EditTagModal.prototype, 'fields', function (items) {
			// Only customize the modal if editing an EXISTING tag.
			if (!result(this, 'tag.exists', false)) {
				return;
			}

			items.replace(
				'name',
				<div className="Form-group">
					<label>{t(`${tagsPrfx}.name_label`)}</label>
					<input type="hidden" value={this.name()} />
					<TranslationKey translation={dynTagKeyPrefix + this.tag.id() + labelSuffix} />
				</div>,
				50
			);

			items.replace(
				'description',
				<div className="Form-group">
					<label>{t(`${tagsPrfx}.description_label`)}</label>
					<input className="FormControl" value={this.description()} type="hidden" />
					<TranslationKey translation={dynTagKeyPrefix + this.tag.id() + descriptionSuffix} />
				</div>,
				30
			);
		});
	}
}
