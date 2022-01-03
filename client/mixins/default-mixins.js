import MixinData from '~/mixins/default-data';
import MixinHead from '~/mixins/default-head';
import MixinCreated from '~/mixins/default-created';
import MixinMethod from '~/mixins/default-method';

export default {
    /* DEFAULT GLOBAL MIXIN */
    mixins : [
        MixinData,
        MixinHead,
        MixinCreated,
        MixinMethod
    ],
}