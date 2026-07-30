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
export function findChild(parent: Object, childClass: string, recursive?: boolean): Object | null;
/**
 * Searches for a child element in a vdom element and returns its index in the `children` array
 *
 * @param {Object} parent vdom element to search in
 * @param {string} childClass Class name of the child element that needs to be found
 *
 * @returns {Number|Boolean} The index of the child element that has been found or `false` if not found
 */
export function childIndex(parent: Object, childClass: string): number | boolean;
/**
 * Get the children of an vdom element. If there are no children, return empty array.
 * @param {Object} parent vdom element to search in
 *
 * @returns {Array} the children
 */
export function getChildren(parent: Object): any[];
