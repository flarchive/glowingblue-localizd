/// <reference types="flarum/@types/translator-icu-rich" />
import Component, { ComponentAttrs } from 'flarum/common/Component';
import type Mithril from 'mithril';
export interface ILinguistTranslateAttrs extends ComponentAttrs {
    key: string;
}
export default class LinguistTranslate<CustomAttrs extends ILinguistTranslateAttrs = ILinguistTranslateAttrs> extends Component<CustomAttrs> {
    private stringKey?;
    private clear;
    private clearing;
    oninit(vnode: Mithril.Vnode): void;
    className(): string;
    title(): import("@askvortsov/rich-icu-message-formatter").NestedStringArray;
    view(): JSX.Element;
}
