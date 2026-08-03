/// <reference types="mithril" />
import Component, { ComponentAttrs } from 'flarum/common/Component';
export interface ITranslationKeyAttrs extends ComponentAttrs {
    translation: string;
}
export default class TranslationKey<CustomAttrs extends ITranslationKeyAttrs = ITranslationKeyAttrs> extends Component<CustomAttrs> {
    view(): JSX.Element;
}
