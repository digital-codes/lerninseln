import os
import skimage.io
from skimage.transform import resize
# use like resize(img,shap)e
import numpy as np
from matplotlib import pyplot as plt

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
SRC="li-logo-white.png"

DST_BASE="/home/kugel/daten/work/mobile/lerninseln/pickUp/android/app/src/main/res/"

logo = skimage.io.imread(SRC)

for s in shapes:
    shape = s["shape"]
    name = s["name"]
    size = min((shape[0],shape[1]))
    bg = np.ndarray(shape)
    bg.fill(1)
    print(name, shape)
    i = resize(logo,(size // 2,size // 2, shape[2]))
    for r in range(size // 2):
        for c in range(size // 2):
            bg[(shape[0] - size // 2) // 2 + r][(shape[1] - size // 2) // 2 + c]=i[r][c]        
    # skimage.io.imshow(bg)
    # plt.show()
    f = DST_BASE + name + "/splash.png"
    try:
        skimage.io.imsave(f,bg)
    except:
        print("Failed on ",name)
        pass





    
