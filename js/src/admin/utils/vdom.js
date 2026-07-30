/*
 * This file is part of glowingblue/localizd.
 *
 * Copyright (c) 2022 Glowing Blue AG.
 * Authors: Ian Morland, Rafael Horvat.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

import result from 'lodash.result';

/**
 * Searches for a child element in a vdom element (object retuerned by
 * the `view()` method of a `Component`, or a child of this original vdom element).
 *
 * @param {Object} parent vdom element to search in
 * @param {string} childClass Class name of the child element that needs to be found
 * @param {Boolean} recursive Whether the search should be done recursively
 *
 * @returns {Object?} The child element that has been found
 */
export function findChild(parent, childClass, recursive = false) {
	if (!childClass) {
		throw Error('A child class needs to be passed as second argument of the findChild function');
	}
	const children = getChildren(parent);
	const child = children.find((child) => {
		const child_className = result(child, 'attrs.className', '');
		return child_className.includes(childClass);
	});

	// Recursive search
	if (recursive && !child) {
		for (let sub_parent of children) {
			const sub_child = findChild(sub_parent, childClass, true);
			if (sub_child) {
				return sub_child;
			}
		}
	}

	return child;
}

/**
 * Searches for a child element in a vdom element and returns its index in the `children` array
 *
 * @param {Object} parent vdom element to search in
 * @param {string} childClass Class name of the child element that needs to be found
 *
 * @returns {Number|Boolean} The index of the child element that has been found or `false` if not found
 */
export function childIndex(parent, childClass) {
	const index = getChildren(parent).indexOf(findChild(parent, childClass));

	// If not found, return false
	if (index === -1) {
		return false;
	}

	return index;
}

/**
 * Get the children of an vdom element. If there are no children, return empty array.
 * @param {Object} parent vdom element to search in
 *
 * @returns {Array} the children
 */
export function getChildren(parent) {
	if (Array.isArray(parent)) {
		return parent;
	}
	const children = result(parent, 'children', []);
	if (!Array.isArray(children)) {
		return [];
	}
	return children;
}
