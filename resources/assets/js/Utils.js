const isNull = (value) => {
    value = Array.isArray(value) ? value : [value];

    return value.indexOf(null) >= 0 || value.indexOf(undefined) >= 0;
};

export {
    isNull
}