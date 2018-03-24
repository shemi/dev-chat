import { prop } from './utils';

const regex = /{(.+?)([?@]?)}(\/?)/g;

/**
 * Parses a given template string and
 * replace dynamic placeholders with actual data.
 *
 * @param {string} template
 * @param {Object} [data]
 */
export default function (template, data) {
    if (typeof template !== 'string') {
        return '';
    }

    return template.replace(regex, (match, key, param, sep) => {
        const value = prop(data, key.trim());

        if (value == null) {
            if (['?', '@'].indexOf(param.trim()) < 0) {
                throw new ReferenceError(`Missing value for ${match}`);
            }

            return param.trim() === '@' ? (key + sep) : '';
        }

        return value + sep;
    });
}