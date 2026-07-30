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
import { components } from '@fof-links';
import { extend } from 'flarum/common/extend';
import TranslationKey from '../components/TranslationKey';
import { slug } from '../../common';

// Make translation calls shorter
const t = app.translator.trans.bind(app.translator);
const prfx = `${slug}.dynamic.links`;
const linksSlug = 'fof-links';
const linksPrfx = `${linksSlug}.admin.edit_link`;

export default function () {
	if (app.initializers.has(linksSlug)) {
		extend(components.EditLinkModal.prototype, 'items', function (items) {
			if (undefined !== this.attrs.link) {
				const gbLinkId = this.attrs.link.data.id;
				items.replace(
					'title',
					[
						<div className="Form-group">
							<label>{t(`${linksPrfx}.title_label`)}</label>
							<TranslationKey translation={`${prfx}.${gbLinkId}.title`} />
						</div>,
					],
					100
				);

				items.replace(
					'url',
					[
						<div className="Form-group">
							<label>{t(`${linksPrfx}.url_label`)}</label>
							<TranslationKey translation={`${prfx}.${gbLinkId}.url`} />
						</div>,
					],
					60
				);
			}
		});
	}
}
