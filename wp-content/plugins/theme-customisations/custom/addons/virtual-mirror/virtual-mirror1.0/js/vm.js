"use strict";
import {Box3, DirectionalLight, Object3D, OrthographicCamera, Scene, Vector3, WebGLRenderer} from "../core/ext_js/three/three.module.min.js";
import {GLTFLoader} from "../core/ext_js/three/loaders/GLTFLoader.min.js";
import "./tensorflow/facemesh.min.js";
import {FaceMeshFaceGeometry} from "./face.min.js";
import "../core/ext_js/camera_utils.min.js";
function addLoadEvent(e) {
    let t = window.onload;
    "function" != typeof window.onload ? window.onload = e : window.onload = function() {
        t && t(),
        e()
    }
}
function getQueryVal(e) {
    let t = window.location.search.substring(1).split("&");
    for (let a = 0; a < t.length; a++) {
        let o = t[a].split("=");
        if (o[0] == e)
            return o[1]
    }
    return !1
}
function getBrowser() {
    var e, t = navigator.userAgent, a = t.match(/(opera|chrome|safari|firefox|msie|trident(?=\/))\/?\s*(\d+)/i) || [];
    if (/trident/i.test(a[1]))
        return e = /\brv[ :]+(\d+)/g.exec(t) || [],
        {
            name: "Explorer",
            version: parseInt(e[1] || "")
        };
    if ("Chrome" === a[1] && null != (e = t.match(/\b(OPR|Edge)\/(\d+)/))) {
        let t = e.slice(1).toString().split(",");
        return {
            name: t[0].replace("OPR", "Opera"),
            version: parseInt(t[1])
        }
    }
    return a = a[2] ? [a[1], a[2]] : [navigator.appName, navigator.appVersion, "-?"],
    null != (e = t.match(/version\/(\d+)/i)) && a.splice(1, 1, e[1]),
    {
        name: a[0],
        version: parseInt(a[1])
    }
}
document.getElementById("usephoto").addEventListener("click", usePhoto),
document.getElementById("deletephoto").addEventListener("click", deleteUserPic),
document.getElementById("usevideo").addEventListener("click", useVideo),
document.getElementById("stopvideo").addEventListener("click", stopVideo),
document.getElementById("filterbyshape").addEventListener("click", filterByShape),
addLoadEvent(i18nInit);
var isBrowser = getBrowser()
  , frameFile = getQueryVal("frameFile") ? getQueryVal("frameFile") : vmConf.defaultFrame;
function setCaching(e) {
    if ("on" == vmConf.STOP_BROWSER_CACHE) {
        let t = 5
          , a = 1e8;
        e += "?" + (Math.floor(Math.random() * (a - t)) + t)
    }
    return e
}
frameFile = setCaching(frameFile);
var frameName = getQueryVal("frameName") ? getQueryVal("frameName") : vmConf.defaultFrameName
  , frameSize = getQueryVal("frameSize") ? getQueryVal("frameSize") : vmConf.defaultFrameSize;
function extension(e) {
    let t, a = e.lastIndexOf("."), o = e.length;
    if (-1 != a && o != a + 1) {
        let a = e.split(".");
        t = a[a.length - 1].toLowerCase()
    } else
        t = "";
    return t
}
const blendMode = ["multiply", "source-over"];
var blend;
function setBlendMode() {
    blend = "png" != extension(frameFile)
}
setBlendMode();
const defaultW = 384
  , defaultH = 446;
var gw, gh;
const maxFrameW = 480
  , maxFrameH = 512;
var faceImg, offsetScale, flip, prevNoseX, prevNoseY, hasUserPic, vid = document.getElementById("videostream"), overlay = document.getElementById("overlay"), overlayCtx = overlay.getContext("2d"), overlay3d = document.getElementById("overlay3d"), firstUserPicLoaded = !1;
const maxUserPicH = 512;
var hqScalerReady, uglyHideAsync = !1, faces = [], kp = [], positions = [], startFacialShapeDetection = !1, i = 0, prevFacialShapeResult = null, topY = [], earsLx = [], earsRx = [], jawLx = [], jawRx = [], chinLx = [], chinRx = [], bottomY = [];
const maxResultLoop = 29;
var filterLink, croppedFrameImg, frameHolder, initRatio, frame, frameCtx, fhX, fhY, webcam, scene, camera, frame3d, renderer, model, faceGeometry, roll, bufRoll, rawFrame = document.createElement("canvas"), frameImg = new Image, onVideo = "on" == localStorage.getItem("videoMode"), useMesh = checkForMesh(frameFile), setup3dDone = !1;
const movedPix = 1 * window.devicePixelRatio;
!0 === useMesh ? (onVideo = !0,
loadFrame2d(!1, vmConf.defaultFrame)) : loadFrame2d(!1, ""),
!1 === onVideo ? addLoadEvent(useVideo) : (addLoadEvent(useVideo),
addLoadEvent(getMobileVideoOrientation));
var spinnerCanvasLoader = new CanvasLoader("canvasloader-container");
spinnerCanvasLoader.setColor("#d3d3d3"),
spinnerCanvasLoader.setShape("square"),
spinnerCanvasLoader.setDiameter(52),
spinnerCanvasLoader.setDensity(12),
spinnerCanvasLoader.setRange(1),
spinnerCanvasLoader.setFPS(12),
spinnerCanvasLoader.setSpeed(1),
toastr.options = {
    positionClass: "toast-top-center",
    extendedTimeOut: "5000",
    newestOnTop: !1,
    preventDuplicates: !0
};
const defaultColor = getStyleFromCss(".dropbtn", "background-color")
  , highlightedColor = getStyleFromCss(".dropdown:hover .dropbtn", "background-color")
  , navbarHeight = document.getElementById("vm-header").offsetHeight
  , shapeDetectionTop = getStyleFromCss("#shapedetection", "top");
var shapeIconCtx;
const shapeIconLayerWidth = 90
  , shapeIconLayerHeight = 90;
function addCanvasForShapeIcon() {
    let e = document.createElement("canvas")
      , t = document.getElementById("shapesymbol");
    e.id = "shapeIconLayer",
    e.width = 90,
    e.height = 90,
    e.style.position = "absolute",
    t.appendChild(e),
    shapeIconCtx = e.getContext("2d")
}
function addCanvasForOverlay3d() {
    let e = document.createElement("canvas")
      , t = document.getElementById("overlay3d");
    e.id = "overlay3dLayer",
    e.width = overlay.width,
    e.height = overlay.height,
    e.style.position = "absolute",
    t.appendChild(e)
}
if (addCanvasForShapeIcon(),
addCanvasForOverlay3d(),
document.body.addEventListener("click", (function(e) {
    document.getElementById("menu").contains(e.target) && (document.getElementById("menu-button").style.backgroundColor = defaultColor,
    document.getElementById("menu").style.display = "none")
}
)),
document.body.addEventListener("mouseover", (function(e) {
    document.getElementById("menu-button").contains(e.target) && ($("#shapedetection").hide(),
    $("#shapedetection").css({
        top: shapeDetectionTop
    }),
    document.getElementById("menu-button").style.backgroundColor = highlightedColor,
    document.getElementById("menu").style.display = "block")
}
)),
document.getElementById("content").addEventListener("mouseleave", (function() {
    document.getElementById("menu-button").style.backgroundColor = defaultColor,
    document.getElementById("menu").style.display = "none"
}
)),
$("#savesnap").attr("title", L("Save Snapshot")),
$("#savesnap").css("cursor", "pointer"),
document.getElementById("savesnap").addEventListener("click", (function() {
    saveSnap(overlay, gw, gh)
}
)),
forMobile()) {
    let e = !1;
    document.body.addEventListener("touchstart", (function(t) {
        document.getElementById("menu-button").contains(t.target) && (!1 === e ? (e = !0,
        document.getElementById("menu-button").style.backgroundColor = highlightedColor,
        document.getElementById("menu").style.display = "block",
        $("#shapedetection").hide(),
        $("#shapedetection").css({
            top: shapeDetectionTop
        })) : (document.getElementById("menu-button").style.backgroundColor = defaultColor,
        document.getElementById("menu").style.display = "none",
        e = !1))
    }
    ))
}
function forMobile() {
    if (sessionStorage.desktop)
        return !1;
    if (localStorage.mobile)
        return !0;
    let e = ["android", "webos", "iphone", "ipad", "ipod", "blackberry", "nokia", "opera mini", "windows mobile", "windows phone", "zunewp7", "iemobile", "tablet", "mobi"];
    for (let t in e)
        if (navigator.userAgent.toLowerCase().indexOf(e[t].toLowerCase()) > 0)
            return !0;
    return !1
}
function setDimension(e, t) {
    onVideo ? ("mobile" == e ? "landscape" == t ? (gw = 640,
    gh = 640,
    $("#overlay3d").hide(),
    toastr.error(L("Due to the resulting parallax, the landscape mode is useless. Please rotate back."))) : (gw = 480,
    gh = 480,
    !0 === useMesh && $("#overlay3d").show()) : "desktop" == e && (gw = 480,
    gh = 480),
    vid.setAttribute("width", gw),
    vid.setAttribute("height", gh),
    overlay.setAttribute("width", gw),
    overlay.setAttribute("height", gh)) : (gw = 384,
    gh = 446),
    offsetScale = 384 / gw;
    let a = gw + 2;
    document.getElementById("content").style.width = a + "px";
    let o = 132;
    o += (gw - 384) / 2,
    document.getElementById("vm-header").style.left = o + "px",
    setShapeSymbolPosition(),
    setSnapIconPosition()
}
function getMobileVideoOrientation() {
    forMobile() && onVideo && (getOrientation(),
    window.addEventListener("orientationchange", (function() {
        getOrientation()
    }
    )))
}
function getOrientation() {
    90 == Math.abs(window.orientation) ? setDimension("mobile", "landscape") : setDimension("mobile", "portrait")
}
function cropImage(e) {
    let t = e.width
      , a = e.height
      , o = document.createElement("canvas");
    o.setAttribute("width", t),
    o.setAttribute("height", a);
    let n = o.getContext("2d");
    n.drawImage(e, 0, 0);
    let i = n.getImageData(0, 0, t, a).data
      , r = function(e, a) {
        let o = t * a + e;
        return {
            red: i[4 * o],
            green: i[4 * o + 1],
            blue: i[4 * o + 2],
            opacity: i[4 * o + 3]
        }
    }
      , s = function(e) {
        return e.red > 200 && e.green > 200 && e.blue > 200
    }
      , l = function(e) {
        let o = e ? 1 : -1;
        for (let n = e ? 0 : a - 1; e ? n < a : n > -1; n += o)
            for (let o = 0; o < t; o++) {
                let t = r(o, n);
                if (!s(t))
                    return e ? n : Math.min(n + 1, a - 1)
            }
        return null
    }
      , d = function(e) {
        let o = e ? 1 : -1;
        for (let n = e ? 0 : t - 1; e ? n < t : n > -1; n += o)
            for (let o = 0; o < a; o++) {
                let a = r(n, o);
                if (!s(a))
                    return e ? n : Math.min(n + 1, t - 1)
            }
        return null
    }
      , c = l(!0)
      , m = l(!1)
      , h = d(!0)
      , p = d(!1) - h
      , u = m - c;
    return o.setAttribute("width", p),
    o.setAttribute("height", u),
    o.getContext("2d").drawImage(e, h, c, p, u, 0, 0, p, u),
    o
}
function setFrameSize(e) {
    croppedFrameImg.width > vmConf.AVERAGE_FRAME_SIZE && (initRatio = gw / croppedFrameImg.width),
    initRatio += computeFrameSize(frameSize),
    initRatio += vmConf.OFFSET_SCALE * initRatio,
    initRatio *= offsetScale,
    frameHolder.setAttribute("width", croppedFrameImg.width * initRatio),
    frameHolder.setAttribute("height", croppedFrameImg.height * initRatio),
    fhX = frameHolder.width / 2,
    fhY = frameHolder.height / 2,
    hqScalerReady = !1,
    pica.resizeCanvas(rawFrame, frameHolder, {
        quality: 3,
        alpha: !0
    }, (function() {
        hqScalerReady = !0,
        !1 === onVideo && (overlayCtx.clearRect(0, 0, gw, gh),
        overlayCtx.drawImage(faceImg, 0, 0, gw, gh)),
        !0 === e ? !1 === hasUserPic ? showFrameAtDefaultPosition() : showFrame2d(roll, positions[0], positions[1], positions[2], positions[3], positions[4], positions[5], positions[6]) : !1 === hasUserPic && !1 === onVideo && (document.getElementById("filterbyshape").className = "disabled",
        showFrameAtDefaultPosition())
    }
    ))
}
function showFrameAtDefaultPosition() {
    hideSpinbox(),
    showFrame2d(0, vmConf.leftBrowX, vmConf.leftBrowY, vmConf.rightBrowX, vmConf.rightBrowY, vmConf.leftEyeX, vmConf.rightEyeX, vmConf.noseCenter)
}
function loadFrame2d(e, t) {
    let a = frameFile;
    t && (a = t),
    frameImg.onload = function() {
        croppedFrameImg = !0 === blend ? cropImage(frameImg) : frameImg,
        rawFrame.width = croppedFrameImg.width,
        rawFrame.height = croppedFrameImg.height,
        rawFrame.getContext("2d").drawImage(croppedFrameImg, 0, 0),
        frameHolder = document.createElement("canvas"),
        frame = document.createElement("canvas"),
        frameCtx = frame.getContext("2d"),
        frame.setAttribute("width", 480),
        frame.setAttribute("height", 512),
        !0 === e && (setBlendMode(),
        setFrameSize(!0))
    }
    ,
    frameImg.onerror = function() {
        toastr.error("Error loading as 2d image: " + this.src)
    }
    ,
    frameImg.crossOrigin = "Anonymous",
    frameImg.src = a
}
function replaceFrame(e, t, a) {
    frameFile = setCaching(e),
    frameName = t,
    frameSize = a,
    frameImg = new Image,
    !0 === (useMesh = checkForMesh(frameFile)) ? (useVideo(!0),
    $("#overlay3d").show()) : ($("#overlay3d").hide(),
    loadFrame2d(!0, ""))
}
function usePhoto(e) {
    overlayCtx.clearRect(0, 0, gw, gh),
    $("#shapesymbol").hide(),
    $("#overlay3d").hide(),
    flipOff(),
    useMesh = !1,
    onVideo = !1,
    startFacialShapeDetection = !1,
    setDimension("desktop", "landscape"),
    overlay.setAttribute("width", gw),
    overlay.setAttribute("height", gh),
    overlay.style.backgroundColor = "#ffffff",
    overlay.style.border = "1px solid " + defaultColor,
    document.getElementById("usephoto").className = "disabled",
    document.getElementById("loadphoto").className = "enabled",
    document.getElementById("filterbyshape").className = "disabled",
    $("#savesnap").hide(),
    document.getElementById("usevideo").className = !0 === e ? "disabled" : "enabled",
    document.getElementById("stopvideo").className = "disabled",
    checkUserPic(),
    localStorage.setItem("videoMode", "off"),
    localStorage.removeItem("facialShapeOnVideo")
}
function checkUserPic() {
    let e = localStorage.getItem("userpic");
    (hasUserPic = null != e) ? (handleUserPic(e, -3, !1),
    document.getElementById("loadphoto").className = "disabled",
    document.getElementById("deletephoto").className = "enabled",
    $("#savesnap").show()) : loadDefaultModel()
}
function handleUserPic(e, t, a) {
    (faceImg = new Image).onload = function() {
        let e = faceImg.width
          , o = faceImg.height
          , n = o / e;
        if (384 != e && (e = 384,
        o = Math.round(e * n)),
        overlay.setAttribute("width", e),
        overlay.setAttribute("height", o),
        showSpinbox(e, o),
        t > 4 && t < 9 && ("Chrome" == isBrowser.name && isBrowser.version < 81 || "Safari" == isBrowser.name && isBrowser.version < 13 || "Firefox" == isBrowser.name && isBrowser.version < 77) && (384 != o && (o = 384,
        e = Math.round(o / n)),
        overlay.setAttribute("width", o),
        overlay.setAttribute("height", e),
        showSpinbox(o, e),
        switchSrcOrientation(t, overlayCtx, e, o)),
        gw = e,
        gh = o,
        overlayCtx.drawImage(faceImg, 0, 0, gw, gh),
        !0 === a) {
            let e = overlay.toDataURL();
            localStorage.base64size = e.length,
            localStorage.setItem("userpic", e)
        }
        if (t > 1 && t < 9)
            checkUserPic();
        else if (!1 === firstUserPicLoaded) {
            firstUserPicLoaded = !0,
            setFrameSize();
            let e = setInterval((function() {
                !0 === hqScalerReady && (clearInterval(e),
                getTrackPhotoReady())
            }
            ), 100)
        } else
            getTrackPhotoReady()
    }
    ,
    faceImg.src = e
}
async function getTrackPhotoReady() {
    await tf.ready(),
    trackPhoto()
}
function switchSrcOrientation(e, t, a, o) {
    switch (e) {
    case 2:
        t.transform(-1, 0, 0, 1, a, 0);
        break;
    case 3:
        t.transform(-1, 0, 0, -1, a, o);
        break;
    case 4:
        t.transform(1, 0, 0, -1, 0, o);
        break;
    case 5:
        t.transform(0, 1, 1, 0, 0, 0);
        break;
    case 6:
        t.transform(0, 1, -1, 0, o, 0);
        break;
    case 7:
        t.transform(0, -1, -1, 0, o, a);
        break;
    case 8:
        t.transform(0, -1, 1, 0, 0, a);
        break
    }
}
function loadDefaultModel() {
    $("#shapedetection").hide(),
    $("#shapesymbol").hide(),
    setDimension("desktop", "landscape"),
    (faceImg = new Image).onload = function() {
        showSpinbox(gw, gh),
        overlay.setAttribute("width", faceImg.width),
        overlay.setAttribute("height", faceImg.height),
        overlayCtx.drawImage(faceImg, 0, 0, faceImg.width, faceImg.height),
        setFrameSize(!1)
    }
    ,
    faceImg.src = "male" == getQueryVal("gender") ? vmConf.defaultModelMale : vmConf.defaultModelFemale
}
async function trackPhoto() {
    if (model = await facemesh.load({
        maxFaces: 1,
        minDetectionConfidence: .5,
        minTrackingConfidence: .7
    }),
    (faces = await model.estimateFaces(overlay)).length > 0) {
        kp = faces[0].scaledMesh,
        hideSpinbox(),
        positions = [Math.round(kp[334][0]), Math.round(kp[334][1]), Math.round(kp[105][0]), Math.round(kp[105][1]), Math.round(kp[463][0]), Math.round(kp[243][0]), Math.round(kp[8][0])],
        showFrame2d(calculateFaceAngle2d(kp).roll, positions[0], positions[1], positions[2], positions[3], positions[4], positions[5], positions[6])
    } else
        hideSpinbox(),
        toastr.error(L("DETECTOR_FAIL"))
}
if (window.replaceFrame = replaceFrame,
window.File && window.FileReader && window.FileList) {
    let e, t = function(t) {
        let a = t.target.files
          , o = [];
        for (let e = 0; e < a.length; e++)
            a[e].type.match("image.*") && o.push(a[e]);
        a.length > 0 && (e = 0),
        loadUserPic(o, e)
    };
    document.getElementById("filedialog").addEventListener("change", t, !1)
}
function loadUserPic(e, t) {
    e.indexOf(t) < 0 && getOrientationFromExif(e[t], (function(a) {
        let o = new FileReader;
        o.onload = (e[t],
        function(e) {
            handleUserPic(e.target.result, a, !0)
        }
        ),
        o.readAsDataURL(e[t]),
        hasUserPic = !0,
        document.getElementById("usephoto").className = "disabled",
        document.getElementById("loadphoto").className = "disabled",
        document.getElementById("deletephoto").className = "enabled",
        $("#savesnap").show()
    }
    ))
}
function getOrientationFromExif(e, t) {
    let a = new FileReader;
    a.onload = function(e) {
        let a = new DataView(e.target.result);
        if (65496 != a.getUint16(0, !1))
            return t(-2);
        let o = a.byteLength
          , n = 2;
        for (; n < o; ) {
            let e = a.getUint16(n, !1);
            if (n += 2,
            65505 == e) {
                if (1165519206 != a.getUint32(n += 2, !1))
                    return t(-1);
                let e = 18761 == a.getUint16(n += 6, !1);
                n += a.getUint32(n + 4, e);
                let o = a.getUint16(n, e);
                n += 2;
                for (let i = 0; i < o; i++)
                    if (274 == a.getUint16(n + 12 * i, e))
                        return t(a.getUint16(n + 12 * i + 8, e))
            } else {
                if (65280 != (65280 & e))
                    break;
                n += a.getUint16(n, !1)
            }
        }
        return t(-1)
    }
    ,
    a.readAsArrayBuffer(e)
}
function deleteUserPic() {
    document.getElementById("usephoto").className = "disabled",
    document.getElementById("deletephoto").className = "disabled",
    document.getElementById("usevideo").className = "enabled",
    $("#savesnap").hide(),
    localStorage.removeItem("userpic"),
    usePhoto()
}
function useVideo(e) {
    flipOn(),
    onVideo = !0,
    uglyHideAsync = !1,
    setDimension("desktop", "landscape"),
    getMobileVideoOrientation(),
    overlay.style.backgroundColor = "#000000",
    overlay.style.border = "1px solid " + defaultColor,
    $("#overlay3d").hide(),
    $("#shapesymbol").hide(),
    document.getElementById("filterbyshape").className = "disabled",
    document.getElementById("usephoto").className = "disabled",
    document.getElementById("loadphoto").className = "disabled",
    document.getElementById("deletephoto").className = "disabled",
    document.getElementById("usevideo").className = "disabled",
    document.getElementById("stopvideo").className = "enabled",
    $("#savesnap").show(),
    setFrameSize(!1),
    initVideo(),
    !0 === useMesh && ($("#overlay3d").show(),
    !1 === setup3dDone && setup3d(),
    loadFrame3d(),
    !0 === e && (renderer.clear(),
    $("#shapesymbol").show(),
    "on" == vmConf.facialShapeProvideLinks && (document.getElementById("filterbyshape").className = "disabled")))
}
async function initVideo() {
    await tf.setBackend("wasm"),
    forMobile() || tf.env().set("WASM_HAS_MULTITHREAD_SUPPORT", !1),
    tf.enableProdMode(),
    model = await facemesh.load({
        maxFaces: 1,
        minDetectionConfidence: .5,
        minTrackingConfidence: .5
    }),
    (webcam = new Camera(vid,{
        onFrame: async()=>{
            trackVideo()
        }
        ,
        width: gw,
        height: gh
    })).start(),
    localStorage.setItem("videoMode", "on"),
    showSpinbox(gw, gh),
    $("#progress").css("width", gw),
    $("#bar").css("width", "0%"),
    $("#progress").show(),
    positions = [],
    prevNoseX = 0,
    prevNoseY = 0
}
async function trackVideo() {
    if (!1 === uglyHideAsync)
        if (faces = !0 === useMesh ? await model.estimateFaces(vid, !1, !0) : await model.estimateFaces(vid),
        overlayCtx.clearRect(0, 0, gw, gh),
        overlayCtx.drawImage(vid, 0, 0, gw, gh),
        faces.length > 0) {
            kp = faces[0].scaledMesh,
            hideSpinbox();
            let e = Math.abs(prevNoseX - kp[8][0])
              , t = Math.abs(prevNoseY - kp[8][1]);
            if ((e > movedPix || t > movedPix) && (positions = [Math.round(kp[334][0]), Math.round(kp[334][1]), Math.round(kp[105][0]), Math.round(kp[105][1]), Math.round(kp[463][0]), Math.round(kp[243][0]), Math.round(kp[8][0])],
            prevNoseX = kp[8][0],
            prevNoseY = kp[8][1],
            0 == useMesh)) {
                let e = calculateFaceAngle2d(kp);
                bufRoll = e.roll
            }
            if (useMesh ? (faceGeometry.update(faces[0]),
            showFrame3d(faceGeometry.track(5, 45, 275))) : showFrame2d(bufRoll, positions[0], positions[1], positions[2], positions[3], positions[4], positions[5], positions[6]),
            "on" == vmConf.getFacialShape && (!1 === startFacialShapeDetection && (startFacialShapeDetection = !0,
            null !== (prevFacialShapeResult = localStorage.getItem("facialShapeOnVideo")) && getFacialShape(prevFacialShapeResult, "", "", "", "", "", "", "", "")),
            null === prevFacialShapeResult && (1 == i && showInitialFacialShapeHint(),
            i <= 29 && (topY[i] = kp[10][1],
            earsLx[i] = kp[447][0],
            earsRx[i] = kp[227][0],
            jawLx[i] = kp[401][0],
            jawRx[i] = kp[177][0],
            chinLx[i] = kp[394][0],
            chinRx[i] = kp[169][0],
            bottomY[i] = kp[175][1],
            i++),
            29 == i))) {
                getFacialShape(null, avgArray(topY), avgArray(earsLx), avgArray(earsRx), avgArray(jawLx), avgArray(jawRx), avgArray(chinLx), avgArray(chinRx), avgArray(bottomY))
            }
        } else
            showSpinbox(gw, gh)
}
function showFrame2d(e, t, a, o, n, i, r, s) {
    let l = (i + (r - i) / 2 + s) / 2
      , d = (a + n) / 2
      , c = (t - o) / 110;
    l = Math.round(l),
    d = Math.round(d + vmConf.OFFSET_Y * c),
    frameCtx.setTransform(1, 0, 0, 1, 0, 0),
    frameCtx.clearRect(0, 0, gw, gh),
    frameCtx.translate(l, d),
    frameCtx.scale(c, c),
    frameCtx.rotate(e),
    frameCtx.drawImage(frameHolder, -fhX, -fhY),
    !0 === blend && (overlayCtx.globalCompositeOperation = blendMode[0]),
    overlayCtx.drawImage(frame, 0, 0),
    !0 === blend && (overlayCtx.globalCompositeOperation = blendMode[1])
}
function showFrame3d(e) {
    frame3d.scale.setScalar(e.scale);
    let t = e.position.x
      , a = e.position.y
      , o = e.position.z;
    frame3d.rotation.setFromRotationMatrix(e.rotation);
    let n = frame3d.rotation;
    t -= 70 * n._y * e.scale,
    t -= 30 * n._z * e.scale,
    a += 80 * n._x * e.scale,
    a += (34 + vmConf.OFFSET_Y_3D) * e.scale,
    frame3d.position.set(t, a, o),
    frame3d.rotation.x += vmConf.OFFSET_GLASSES_PRE_TILT,
    renderer.render(scene, camera)
}
function calculateFaceAngle2d(e) {
    var t, a, o, n;
    return {
        roll: (t = e[33][0],
        a = e[33][1],
        o = e[263][0],
        n = e[263][1],
        Math.atan2(n - a, o - t))
    }
}
export default function videoFail() {
    toastr.error(L("NO_CAM_FOUND")),
    toastr.success(L("ENABLE_PHOTO_MODE")),
    usePhoto(!0)
}
function setup3d() {
    scene = new Scene,
    (renderer = new WebGLRenderer({
        powerPreference: "high-performance",
        antialias: !0,
        alpha: !0,
        preserveDrawingBuffer: !0
    })).setPixelRatio(window.devicePixelRatio),
    renderer.setSize(gw, gh),
    (camera = new OrthographicCamera(1,1,1,1,-1e3,1e3)).left = -.5 * gw,
    camera.right = .5 * gw,
    camera.top = .5 * gh,
    camera.bottom = -.5 * gh,
    camera.updateProjectionMatrix();
    const e = new DirectionalLight(16777215,4);
    e.position.set(0, 0, 1),
    scene.add(e),
    overlay3d.appendChild(renderer.domElement),
    frame3d = new Object3D,
    scene.add(frame3d),
    (faceGeometry = new FaceMeshFaceGeometry).setSize(gw, gh),
    setup3dDone = !0
}
function loadFrame3d() {
    (new GLTFLoader).load(frameFile, (function(e) {
        frame3d.clear();
        let t = (new Box3).setFromObject(e.scene);
        const a = new Vector3;
        t.getSize(a);
        let o = 180 / a.length();
        o += computeFrameSize(frameSize) * o + vmConf.OFFSET_SCALE_3D,
        e.scene.scale.set(o, o, o);
        const n = new Vector3;
        t.getCenter(n).multiplyScalar(o),
        e.scene.position.sub(n),
        frame3d.add(e.scene),
        $("#progress").hide()
    }
    ), (function(e) {
        let t = Math.round(e.loaded / e.total * 100);
        $("#bar").css("width", t + "%")
    }
    ), (function() {
        toastr.error("Error loading as 3d image: " + frameFile)
    }
    ))
}
function stopVideo() {
    uglyHideAsync = !0,
    onVideo = !1,
    hideSpinbox(),
    webcam.stop(),
    document.getElementById("usephoto").className = "enabled",
    document.getElementById("loadphoto").className = "disabled",
    document.getElementById("usevideo").className = "disabled",
    document.getElementById("stopvideo").className = "disabled",
    $("#savesnap").show()
}
function computeFrameSize(e) {
    let t;
    return t = e > 0 ? .005 * e - .65 : 0,
    t
}
function showSpinbox(e, t) {
    let a = document.getElementById("canvasLoader");
    a.style.position = "absolute",
    a.style.left = e / 2 + -.5 * spinnerCanvasLoader.getDiameter() + "px",
    a.style.top = t / 2 - navbarHeight + -.5 * spinnerCanvasLoader.getDiameter() + "px",
    spinnerCanvasLoader.show()
}
function hideSpinbox() {
    spinnerCanvasLoader.hide()
}
function flipOn() {
    flip = !0,
    $(overlay).css({
        OTransform: "scaleX(-1)",
        webkitTransform: "scaleX(-1)",
        transform: "scaleX(-1)",
        "ms-filter": "fliph",
        filter: "fliph"
    })
}
function flipOff() {
    flip = !1,
    $(overlay).css({
        OTransform: "",
        webkitTransform: "",
        transform: "",
        "ms-filter": "",
        filter: ""
    })
}
function saveSnap(e, t, a) {
    onVideo && stopVideo();
    let o = document.createElement("canvas");
    o.width = t,
    o.height = a;
    let n = o.getContext("2d");
    if (flip && (n.translate(t, 0),
    n.scale(-1, 1)),
    n.drawImage(e, 0, 0),
    !0 === useMesh) {
        let e = document.createElement("canvas");
        e.width = t,
        e.height = a;
        let o = e.getContext("2d");
        if (flip) {
            let e = 1 / window.devicePixelRatio;
            o.translate(t, 0),
            o.scale(-e, e)
        }
        o.drawImage(renderer.domElement, 0, 0),
        n.drawImage(e, 0, 0)
    }
    o.toBlob((function(e) {
        let t = decodeURIComponent(frameName);
        t = t.split(" ").join("-"),
        t += ".png",
        saveAs(e, t, !0)
    }
    )),
    document.getElementById("usephoto").className = "disabled",
    document.getElementById("usevideo").className = "enabled"
}
function getFacialShape(e, t, a, o, n, i, r, s, l) {
    let d = "";
    if (null === e) {
        let e = l - t
          , c = a - o
          , m = Math.round(e / c * 100) / 100
          , h = n - i
          , p = r - s
          , u = Math.round(c / h * 100) / 100
          , f = Math.round(c / p * 100) / 100;
        m <= 1.16 && f <= 1.72 && (d = "round"),
        d || u <= 1.05 && f <= 1.72 && (d = "angular"),
        d || f > 1.72 && (d = "heart-shaped"),
        d || (d = "oval"),
        assignFacialShapeResult(!1, d),
        !0 === onVideo && localStorage.setItem("facialShapeOnVideo", d)
    } else
        assignFacialShapeResult(!0, e)
}
function assignFacialShapeResult(e, t) {
    let a, o, n;
    switch (t) {
    case "round":
        a = L(t),
        o = L("Round Face"),
        n = L("rectangle"),
        filterLink = vmConf.roundFaceLink;
        break;
    case "angular":
        a = L(t),
        o = L("Angular Face"),
        n = L("Cat Eye or Aviator Style"),
        filterLink = vmConf.angularFaceLink;
        break;
    case "heart-shaped":
        a = L(t),
        o = L("Heart-Shaped Face"),
        n = L("oval or round"),
        filterLink = vmConf.heartShapedFaceLink;
        break;
    case "oval":
        a = L(t),
        o = L("Oval Face"),
        n = L("not specified. Every frame shape should fit"),
        filterLink = vmConf.ovalFaceLink;
        break
    }
    makeShapeDetectionResult(e, t, o, n),
    "on" == vmConf.facialShapeProvideLinks && (document.getElementById("filterbyshape").className = "disabled")
}
function makeShapeDetectionResult(e, t, a, o) {
    $("#shapesymbol").hide(),
    shapeIconCtx.clearRect(0, 0, 90, 90),
    setShapeSymbolPosition();
    let n = 45
      , i = 30
      , r = 54;
    switch (shapeIconCtx.fillStyle = "red",
    shapeIconCtx.font = "normal normal 13px Droid Sans",
    shapeIconCtx.textAlign = "center",
    shapeIconCtx.strokeStyle = "red",
    shapeIconCtx.lineWidth = 3,
    t) {
    case "round":
        r += 4;
        const e = 12;
        shapeIconCtx.beginPath(),
        shapeIconCtx.arc(n, i, e, 0, 2 * Math.PI, !1),
        shapeIconCtx.closePath(),
        shapeIconCtx.stroke();
        break;
    case "angular":
        n -= 10,
        i -= 18;
        const t = 24
          , a = 20;
        let o = 5;
        o = {
            tl: o,
            tr: o,
            br: o,
            bl: o
        },
        shapeIconCtx.beginPath(),
        shapeIconCtx.moveTo(n + o.tl, i),
        shapeIconCtx.lineTo(n + a - o.tr, i),
        shapeIconCtx.quadraticCurveTo(n + a, i, n + a, i + o.tr),
        shapeIconCtx.lineTo(n + a, i + t - o.br),
        shapeIconCtx.quadraticCurveTo(n + a, i + t, n + a - o.br, i + t),
        shapeIconCtx.lineTo(n + o.bl, i + t),
        shapeIconCtx.quadraticCurveTo(n, i + t, n, i + t - o.bl),
        shapeIconCtx.lineTo(n, i + o.tl),
        shapeIconCtx.quadraticCurveTo(n, i, n + o.tl, i),
        shapeIconCtx.closePath(),
        shapeIconCtx.stroke();
        break;
    case "heart-shaped":
        i -= 22,
        r -= 4;
        const s = 24
          , l = 28;
        shapeIconCtx.beginPath();
        const d = .3 * l;
        shapeIconCtx.moveTo(n, i + d),
        shapeIconCtx.bezierCurveTo(n, i, n - s / 2, i, n - s / 2, i + d),
        shapeIconCtx.bezierCurveTo(n - s / 2, i + (l + d) / 2, n, i + (l + d) / 2, n, i + l),
        shapeIconCtx.bezierCurveTo(n, i + (l + d) / 2, n + s / 2, i + (l + d) / 2, n + s / 2, i + d),
        shapeIconCtx.bezierCurveTo(n + s / 2, i, n, i, n, i + d),
        shapeIconCtx.closePath(),
        shapeIconCtx.stroke();
        break;
    case "oval":
        r += 4,
        shapeIconCtx.beginPath();
        const c = 24
          , m = 24;
        shapeIconCtx.moveTo(n, i - m / 2),
        shapeIconCtx.bezierCurveTo(n + c / 2, i - m / 2, n + c / 2, i + m / 2, n, i + m / 2),
        shapeIconCtx.bezierCurveTo(n - c / 2, i + m / 2, n - c / 2, i - m / 2, n, i - m / 2),
        shapeIconCtx.closePath(),
        shapeIconCtx.stroke();
        break
    }
    if ("heart-shaped" == t) {
        const e = a.split(" ", 2);
        shapeIconCtx.fillText(e[0], 45, r, 90),
        shapeIconCtx.fillText(e[1], 45, r + 13, 90)
    } else
        shapeIconCtx.fillText(a, 45, r, 90);


    $("#shapesymbol").fadeIn(300);
    /*"on" == vmConf.facialShapeProvideLinks && ($("#shapesymbol").css("cursor", "pointer"),
    $("#shapesymbol").attr("title", L("Tap here for suitable glasses.")),
    $("#shapesymbol").click((function() {
        filterByShape()
    }
    ))),
    !1 === e ? (setTimeout((function() {
        showFinalFacialShapeHint(t, o),
        $("#shapesymbol").fadeIn(300)
    }
    ), 3e3),
    setTimeout((function() {
        $("#shapedetection").fadeOut(600)
    }
    ), 12e3)) : $("#shapesymbol").fadeIn(300)*/
}
function showInitialFacialShapeHint() {
    i18nInit();
    let e = "<span>" + L("Please look straight into the camera!") + "<br />" + L("Detecting face shape") + '</span><span id="result"></span>';
    $("#shapedetection").html(""),
    $("#shapedetection").fadeIn(300, (function() {
        $("#shapedetection").animate({
            top: "82px"
        }, 300),
        $("#shapedetection").html(e),
        showProgressAnimation()
    }
    ))
}
function showProgressAnimation() {
    let e = 0
      , t = setInterval((function() {
        let a = "";
        e++;
        for (let t = 0; t < e % 4; t++)
            a += ".";
        e < 8 ? $("#result").text(a) : (clearInterval(t),
        $("#shapedetection").hide(),
        $("#shapedetection").css({
            top: shapeDetectionTop
        }))
    }
    ), 400)
}
function showFinalFacialShapeHint(e, t) {
    i18nInit();
    let a = L("A") + " <span>" + L(e) + " " + L("face") + "</span> " + L("was detected.") + " "
      , o = L("The recommended spectacle frame shape is") + " " + t + ". "
      , n = "";
    "on" == vmConf.facialShapeProvideLinks && (n = L("Tap here for suitable glasses.")),
    $("#shapedetection").html(""),
    $("#shapedetection").fadeIn(300, (function() {
        $("#shapedetection").animate({
            top: "82px"
        }, 300),
        $("#shapedetection").html(a + o + n),
        "on" == vmConf.facialShapeProvideLinks && ($("#shapesymbol").attr("title", n),
        $("#shapedetection").css("cursor", "pointer"),
        $("#shapedetection").click((function() {
            filterByShape()
        }
        )))
    }
    ))
}
function setShapeSymbolPosition() {
    let e = gw
      , t = gh;
    !1 === onVideo && gh > 512 && (t = 515);
    let a = document.getElementById("shapesymbol");
    a.style.top = t - 90 + "px",
    a.style.left = e - 90 + "px"
}
function filterByShape() {
    onVideo && stopVideo(),
    window.open().location.href = filterLink
}
function setSnapIconPosition() {
    let e = document.getElementById("savesnap");
    e.style.top = "7px",
    e.style.left = gw - 49 + "px"
}
function getStyleFromCss(e, t) {
    let a = e.toLowerCase()
      , o = "." === a.substr(0, 1) ? a.substr(1) : "." + a;
    for (let e = 0; e < document.styleSheets.length; e++) {
        let n = document.styleSheets[e]
          , i = n.cssRules ? n.cssRules : n.rules;
        for (let e = 0; e < i.length; e++)
            if (i[e].selectorText) {
                switch (i[e].selectorText.toLowerCase()) {
                case a:
                case o:
                    return i[e].style[t]
                }
            }
    }
}
function checkForMesh(e) {
    let t = !1;
    return "gltf" != extension(e) && "glb" != extension(e) || (t = !0),
    t
}
function avgArray(e) {
    let t, a = 0;
    for (t = 0; t < e.length; t++)
        a += parseFloat(e[t]);
    return a / t
}
