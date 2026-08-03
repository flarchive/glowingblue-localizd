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
import { backoffice as taxonomiesBackoffice } from '@flamarkt-taxonomies';
import { extend } from 'flarum/common/extend';
import { slug } from '../../common';
import TranslationKey from '../components/TranslationKey';

// Make translation calls shorter
const t = (...args) => app.translator.trans(...args);
const prfx = `${slug}.dynamic.taxonomy`;
const taxSlug = 'flamarkt-taxonomies';
const taxPrfx = `${taxSlug}.admin.edit-taxonomy.field`;

export default function () {
	if (app.initializers.has(taxSlug)) {
		const EditTaxonomyModal = taxonomiesBackoffice['components/EditTaxonomyModal'];
		extend(EditTaxonomyModal.prototype, 'formItems', function (items) {
			if (undefined !== this.attrs.taxonomy) {
				const gbTaxonomyId = this.attrs.taxonomy.id();

				items.replace(
					'name',
					[
						<div className="Form-group">
							<label>{t(`${taxPrfx}.name`)}</label>
							<TranslationKey translation={`${prfx}.${gbTaxonomyId}.name`} />
						</div>,
					],
					95
				);

				items.replace(
					'description',
					[
						<div className="Form-group">
							<label>{t(`${taxPrfx}.description`)}</label>
							<TranslationKey translation={`${prfx}.${gbTaxonomyId}.description`} />
						</div>,
					],
					85
				);
			}
		});

		const EditTermModal = taxonomiesBackoffice['components/EditTermModal'];
		extend(EditTermModal.prototype, 'formItems', function (items) {
			if (undefined !== this.attrs.term) {
				const gbTermId = this.attrs.term.id();

				items.replace(
					'name',
					[
						<div className="Form-group">
							<label>{t(`${taxPrfx}.name`)}</label>
							<TranslationKey translation={`${prfx}-term.${gbTermId}.name`} />
						</div>,
					],
					100
				);

				items.replace(
					'description',
					[
						<div className="Form-group">
							<label>{t(`${taxPrfx}.description`)}</label>
							<TranslationKey translation={`${prfx}-term.${gbTermId}.description`} />
						</div>,
					],
					90
				);
			}
		});
	}
}
