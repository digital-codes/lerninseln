import os
import skimage.io
from skimage.transform import resize
# use like resize(img,shap)e
import json

shapes = [
    {
        "name": "drawable-port-xxhdpi",
        "shape": [
            1600,
            960,
            4
        ]
    },
    {
        "name": "drawable-land-xhdpi",
        "shape": [
            720,
            1280,
            4
        ]
    },
    {
        "name": "drawable-land-mdpi",
        "shape": [
            320,
            480,
            4
        ]
    },
    {
        "name": "drawable-land-hdpi",
        "shape": [
            480,
            800,
            4
        ]
    },
    {
        "name": "drawable-land-xxhdpi",
        "shape": [
            960,
            1600,
            4
        ]
    },
    {
        "name": "drawable-port-xxxhdpi",
        "shape": [
            1920,
            1280,
            4
        ]
    },
    {
        "name": "drawable-port-hdpi",
        "shape": [
            800,
            480,
            4
        ]
    },
    {
        "name": "drawable-port-mdpi",
        "shape": [
            480,
            320,
            4
        ]
    },
    {
        "name": "drawable-land-xxxhdpi",
        "shape": [
            1280,
            1920,
            4
        ]
    },
    {
        "name": "drawable-port-xhdpi",
        "shape": [
            1280,
            720,
            4
        ]
    }
]


SRC="insel-logo-white.png"

DST_BASE="/home/kugel/daten/work/mobile/lerninseln/pickUp/android/app/src/main/res/"

logo = skimage.io.imread(SRC)

for s in shapes:
    shape = s["shape"]
    name = s["name"]
    print(name, shape)
    i = resize(logo,shape)
    f = DST_BASE + name + "/splash.png"
    try:
        skimage.io.imsave(f,i)
    except:
        print("Failed on ",name)
        pass





    
