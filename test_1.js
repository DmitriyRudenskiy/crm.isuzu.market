var jsdom = require("jsdom");
var window = new jsdom.JSDOM('<!DOCTYPE html><head></head></body>').window;

console.log(window.document );