/* 
 * create all the available block types
 */

// define block types for easy building
var BASE = 0;
var PAD_FULL = 1;
var PAD_TRP = 2;
var PAD_DBL = 3;
var PAD_QTR = 4;
var BLOCK_FULL = 5;
var BLOCK_TRP = 6;
var BLOCK_DBL = 7;
var BLOCK_QTR = 8;
var BLOCK_MIN = 9;
var BLOCK_SIN = 10;
var BLOCK_CIRCLE = 11;
var BLOCK_E = 12;
var DOOR = 13;
var WINDOW = 14;
var BLOCK_HC = 15;
var BLOCK_ARCH = 64; // does not have its own index for GPU
var LADDER = 65;
var FLOWER = 66;
var DOG = 67;
var FENCE = 68;

// defing block heights for climbing easier
var PEG = 5/16*2; // a peg and surrounding space, like the 'grid'. used to snap to coordinates
var PAD_HEIGHT_DEF = 3/8; // default pad height
var PAD_HEIGHT = PAD_HEIGHT_DEF; // editable pad height
var BLOCK_HEIGHT_DEF = 3/4; // default block height
var BLOCK_HEIGHT = BLOCK_HEIGHT_DEF; // editable block height

// setup block objects
var block = [];
block[BASE] = {};
block[BASE].index = 1; // index is associated to object[], i.e a box()
block[BASE].h = PAD_HEIGHT;
block[BASE].w = PEG*8;
block[BASE].l = PEG*8;

block[PAD_FULL] = {};
block[PAD_FULL].index = 2; // index is associated to object[], i.e a box()
block[PAD_FULL].h = PAD_HEIGHT;
block[PAD_FULL].w = PEG*8;
block[PAD_FULL].l = PEG*2;

block[PAD_TRP] = {};
block[PAD_TRP].index = 3; // index is associated to object[], i.e a box()
block[PAD_TRP].h = PAD_HEIGHT;
block[PAD_TRP].w = PEG*6;
block[PAD_TRP].l = PEG*2;

block[PAD_DBL] = {};
block[PAD_DBL].index = 4; // index is associated to object[], i.e a box()
block[PAD_DBL].h = PAD_HEIGHT;
block[PAD_DBL].w = PEG*4;
block[PAD_DBL].l = PEG*2;

block[PAD_QTR] = {};
block[PAD_QTR].index = 5; // index is associated to object[], i.e a box()
block[PAD_QTR].h = PAD_HEIGHT;
block[PAD_QTR].w = PEG*2;
block[PAD_QTR].l = PEG*2;

block[BLOCK_FULL] = {};
block[BLOCK_FULL].index = 6; // index is associated to object[], i.e a box()
block[BLOCK_FULL].h = BLOCK_HEIGHT;
block[BLOCK_FULL].w = PEG*8;
block[BLOCK_FULL].l = PEG*2;

block[BLOCK_TRP] = {};
block[BLOCK_TRP].index = 7; // index is associated to object[], i.e a box()
block[BLOCK_TRP].h = BLOCK_HEIGHT;
block[BLOCK_TRP].w = PEG*6;
block[BLOCK_TRP].l = PEG*2;

block[BLOCK_DBL] = {};
block[BLOCK_DBL].index = 8; // index is associated to object[], i.e a box()
block[BLOCK_DBL].h = BLOCK_HEIGHT;
block[BLOCK_DBL].w = PEG*4; // four pegs wide
block[BLOCK_DBL].l = PEG*2; // four pegs long

block[BLOCK_QTR] = {};
block[BLOCK_QTR].index = 9; // index is associated to object[], i.e a box()
block[BLOCK_QTR].h = BLOCK_HEIGHT;
block[BLOCK_QTR].w = PEG*2;
block[BLOCK_QTR].l = PEG*2;

block[BLOCK_MIN] = {};
block[BLOCK_MIN].index = 10; // index is associated to object[], i.e a box()
block[BLOCK_MIN].h = BLOCK_HEIGHT*2;
block[BLOCK_MIN].w = PEG*2;
block[BLOCK_MIN].l = PEG;

block[BLOCK_SIN] = {};
block[BLOCK_SIN].index = 11; // index is associated to object[], i.e a uvCylinder()
block[BLOCK_SIN].h = BLOCK_HEIGHT;
block[BLOCK_SIN].w = PEG;
block[BLOCK_SIN].l = PEG;

block[BLOCK_CIRCLE] = {};
block[BLOCK_CIRCLE].index = 12; // index is associated to object[], i.e a uvCylinder()
block[BLOCK_CIRCLE].h = BLOCK_HEIGHT;
block[BLOCK_CIRCLE].w = PEG;
block[BLOCK_CIRCLE].l = PEG;

block[BLOCK_E] = {};
block[BLOCK_E].index = 13; // index is associated to object[], i.e a uvCylinder()
block[BLOCK_E].h = BLOCK_HEIGHT;
block[BLOCK_E].w = PEG*4;
block[BLOCK_E].l = PEG;

block[DOOR] = {};
block[DOOR].index = 14; // index is associated to object[], i.e a uvCylinder()
block[DOOR].h = BLOCK_HEIGHT*5;
block[DOOR].w = PEG*4;
block[DOOR].l = PEG*2;

block[WINDOW] = {};
block[WINDOW].index = 15; // index is associated to object[], i.e a uvCylinder()
block[WINDOW].h = BLOCK_HEIGHT*3;
block[WINDOW].w = PEG*4;
block[WINDOW].l = PEG*2;

block[BLOCK_HC] = {};
block[BLOCK_HC].index = 16; // index is associated to object[], i.e a uvCylinder()
block[BLOCK_HC].h = BLOCK_HEIGHT*2;
block[BLOCK_HC].w = PEG*2;
block[BLOCK_HC].l = PEG*2;
