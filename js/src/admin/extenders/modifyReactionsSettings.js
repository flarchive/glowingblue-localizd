/*
 * This file is part of glowingblue/localizd.
 *
 * Copyright (c) 2023 Glowing Blue AG.
 * Authors: Rafael Horvat.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

import app from 'flarum/admin/app';
import { components } from '@fof-reactions';
import { extend } from 'flarum/common/extend';
import TranslationKey from '../components/TranslationKey';
import { slug } from '../../common';
import { findChild } from '../utils/vdom';

// Make translation calls shorter
const prfx = `${slug}.dynamic.reactions`;

export default function () {
	if (app.initializers.has('fof/reactions')) {
		extend(components.SettingsPage.prototype, 'view', function (vnode) {
			const container = findChild(vnode, 'Reactions--Container', true);
			const items = container.children[0]?.children || [];

			items.forEach((item) => {
				const reactionsItem = findChild(item, 'Reactions--item', true);
				if (!reactionsItem) {
					return;
				}

				const reactionId = reactionsItem.attrs['data-id'];
				if (!reactionId) {
					return;
				}

				// The first child is the 'display' input, we want to replace it with our own
				// component that will allow us to use a translation key instead of a string
				reactionsItem.children[0] = <TranslationKey translation={`${prfx}.${reactionId}.display`} />;
			});
		});
	}
}
