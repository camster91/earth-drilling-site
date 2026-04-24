/* global Foundation */

const { queries } = Foundation.MediaQuery;
const breakpoints = {};
queries.forEach((query) => {
  breakpoints[query.name] = query.value.match(/\d+/g).map(Number)[0] * 16;
});

export default breakpoints;
