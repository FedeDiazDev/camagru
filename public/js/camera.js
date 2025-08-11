const video = document.getElementById('video')
const canvas = document.getElementById('canvas')
const ctx = canvas.getContext('2d')
const capturedImage = document.getElementById('capturedImage')
const stickerPreview = document.getElementById('stickerPreview')

const startCameraBtn = document.getElementById('startCameraBtn')
const captureBtn = document.getElementById('captureBtn')
const retakeBtn = document.getElementById('retakeBtn')
const downloadBtn = document.getElementById('downloadBtn')
const shareBtn = document.getElementById('shareBtn')
const uploadInput = document.getElementById('uploadInput')

const brightnessInput = document.getElementById('brightness')
const contrastInput = document.getElementById('contrast')
const brightnessValue = document.getElementById('brightnessValue')
const contrastValue = document.getElementById('contrastValue')

const stickersContainer = document.getElementById('stickers')
const filtersContainer = document.getElementById('filters')

const imageContainer = document.getElementById('imageContainer')

const darkStickers = ["🌙", "💀", "🖤", "⚡", "🔮", "🌟", "👁️", "🕷️", "🦇", "🔥", "💜", "🌌"]
const filters = [
    { name: "none", label: "Original" },
    { name: "noir", label: "Noir" },
    { name: "gothic", label: "Gothic" },
    { name: "shadow", label: "Shadow" },
    { name: "mystic", label: "Mystic" }
]

let isCapturing = false
let selectedSticker = null
let selectedFilter = "none"
let brightness = 100
let contrast = 100

let movableStickers = []

function getFilterStyle() {
    let base = `brightness(${brightness}%) contrast(${contrast}%)`
    switch (selectedFilter) {
        case "noir":
            return `${base} grayscale(100%) contrast(120%)`
        case "gothic":
            return `${base} sepia(30%) hue-rotate(270deg) saturate(150%)`
        case "shadow":
            return `${base} brightness(70%) contrast(130%)`
        case "mystic":
            return `${base} hue-rotate(240deg) saturate(120%)`
        default:
            return base
    }
}

function applyFilter() {
    if (isCapturing) {
        video.style.filter = getFilterStyle()
    } else {
        capturedImage.style.filter = getFilterStyle()
        movableStickers.forEach(s => s.element.style.filter = getFilterStyle())
    }
}

function updateBrightness(value) {
    brightness = value
    brightnessValue.textContent = `${value}%`
    applyFilter()
}

function updateContrast(value) {
    contrast = value
    contrastValue.textContent = `${value}%`
    applyFilter()
}

async function startCamera() {
    try {
        const stream = await navigator.mediaDevices.getUserMedia({ video: { width: 640, height: 480 } })
        video.srcObject = stream
        isCapturing = true
        startCameraBtn.classList.add('hidden')
        captureBtn.classList.remove('hidden')
        retakeBtn.classList.add('hidden')
        downloadBtn.classList.add('hidden')
        shareBtn.classList.add('hidden')
        capturedImage.classList.add('hidden')
        video.classList.remove('hidden')
        clearAllMovableStickers()
        applyFilter()
    } catch (err) {
        alert('Could not access camera. Please check permissions.')
        console.error(err)
    }
}

function stopCamera() {
    if (video.srcObject) {
        video.srcObject.getTracks().forEach(track => track.stop())
        video.srcObject = null
    }
    isCapturing = false
    startCameraBtn.classList.remove('hidden')
    captureBtn.classList.add('hidden')
}

let currentDraggingSticker = null
let offsetX, offsetY

function clearAllMovableStickers() {
    movableStickers.forEach(s => imageContainer.removeChild(s.element))
    movableStickers = []
}

function addMovableSticker(stickerEmoji) {
    const sticker = document.createElement('span')
    sticker.className = 'movable-sticker'
    sticker.textContent = stickerEmoji
    sticker.style.position = 'absolute'
    sticker.style.fontSize = '48px'
    sticker.style.cursor = 'move'
    sticker.style.userSelect = 'none'

    const baseWidth = capturedImage.clientWidth || 640;
    const baseHeight = capturedImage.clientHeight || 480;

    sticker.style.left = (baseWidth / 2 - 24) + 'px';
    sticker.style.top = (baseHeight / 2 - 24) + 'px';


    sticker.addEventListener('mousedown', (e) => {
        currentDraggingSticker = sticker
        offsetX = e.clientX - sticker.getBoundingClientRect().left
        offsetY = e.clientY - sticker.getBoundingClientRect().top
        e.preventDefault()
    })

    sticker.addEventListener('dragstart', e => e.preventDefault())

    imageContainer.appendChild(sticker)

    movableStickers.push({ element: sticker, emoji: stickerEmoji, x: parseFloat(sticker.style.left), y: parseFloat(sticker.style.top) })
}

window.addEventListener('mouseup', () => {
    currentDraggingSticker = null
})

window.addEventListener('mousemove', (e) => {
    if (!currentDraggingSticker) return
    const rect = imageContainer.getBoundingClientRect()
    let left = e.clientX - rect.left - offsetX
    let top = e.clientY - rect.top - offsetY

    left = Math.max(0, Math.min(left, imageContainer.clientWidth - currentDraggingSticker.clientWidth))
    top = Math.max(0, Math.min(top, imageContainer.clientHeight - currentDraggingSticker.clientHeight))

    currentDraggingSticker.style.left = left + 'px'
    currentDraggingSticker.style.top = top + 'px'

    const found = movableStickers.find(s => s.element === currentDraggingSticker)
    if (found) {
        found.x = left
        found.y = top
    }
})

function capturePhoto() {
    if (!isCapturing) return;

    if (movableStickers.length === 0) {
        alert("Por favor, añade al menos un sticker antes de tomar la foto.");
        return;
    }

    const realWidth = video.videoWidth;
    const realHeight = video.videoHeight;

    canvas.width = realWidth;
    canvas.height = realHeight;
    ctx.filter = getFilterStyle();
    ctx.drawImage(video, 0, 0, realWidth, realHeight);
   
    const displayWidth = capturedImage.clientWidth || imageContainer.clientWidth;
    const displayHeight = capturedImage.clientHeight || imageContainer.clientHeight;

    const scaleX = realWidth / displayWidth;
    const scaleY = realHeight / displayHeight;

    movableStickers.forEach(sticker => {
        ctx.font = '48px Arial';
        ctx.fillText(sticker.emoji, sticker.x * scaleX, (sticker.y + 40) * scaleY);
    });

    const imageData = canvas.toDataURL('image/png');
    capturedImage.src = imageData;
    capturedImage.classList.remove('hidden');
    video.classList.add('hidden');

    clearAllMovableStickers();

    captureBtn.classList.add('hidden');
    retakeBtn.classList.remove('hidden');
    downloadBtn.classList.remove('hidden');
    shareBtn.classList.remove('hidden');

    stopCamera();
}



function retakePhoto() {
    capturedImage.classList.add('hidden')
    retakeBtn.classList.add('hidden')
    downloadBtn.classList.add('hidden')
    shareBtn.classList.add('hidden')

    clearAllMovableStickers()
    startCamera()
}

function downloadPhoto() {
    if (movableStickers.length === 0) {
        alert("Por favor, añade al menos un sticker antes de tomar la foto.");
        return;
    }
    const link = document.createElement('a')
    link.href = capturedImage.src
    link.download = `darkstudio_${Date.now()}.png`
    link.click()
}

function sharePhoto() {
    if (movableStickers.length === 0) {
        alert("Por favor, añade al menos un sticker antes de tomar la foto.");
        return;
    }
    if (navigator.share) {
        navigator.share({
            title: 'Dark Studio Photo',
            text: 'Check out my photo from Dark Studio!',
            files: [dataURLtoFile(capturedImage.src, 'photo.png')],
        }).catch(console.error)
    } else {
        alert('Sharing not supported on this browser.')
    }
}

function dataURLtoFile(dataurl, filename) {
    let arr = dataurl.split(','), mime = arr[0].match(/:(.*?);/)[1],
        bstr = atob(arr[1]), n = bstr.length, u8arr = new Uint8Array(n)
    while (n--) {
        u8arr[n] = bstr.charCodeAt(n)
    }
    return new File([u8arr], filename, { type: mime })
}

function handleUpload(event) {

    const file = event.target.files[0]
    if (!file) return
    const reader = new FileReader()
    reader.onload = (e) => {
        capturedImage.src = e.target.result
        capturedImage.classList.remove('hidden')
        video.classList.add('hidden')

        clearAllMovableStickers()
        startCameraBtn.classList.remove('hidden')
        captureBtn.classList.add('hidden')
        retakeBtn.classList.remove('hidden')
        downloadBtn.classList.remove('hidden')
        shareBtn.classList.remove('hidden')

    }
    reader.readAsDataURL(file)
}

function createStickerButtons() {
    stickersContainer.innerHTML = ''
    darkStickers.forEach(sticker => {
        const btn = document.createElement('button')
        btn.textContent = sticker
        btn.className = 'sticker-btn cursor-pointer'
        btn.style.fontSize = '24px'
        btn.addEventListener('click', () => {
            addMovableSticker(sticker)
        })
        stickersContainer.appendChild(btn)
    })
}

function createFilterButtons() {
    filters.forEach(filter => {
        const btn = document.createElement('button')
        btn.textContent = filter.label
        btn.type = 'button'
        btn.className = 'px-3 py-1 rounded bg-gray-700 hover:bg-purple-700 text-white text-sm'
        btn.addEventListener('click', () => {
            selectedFilter = filter.name
            applyFilter()
            Array.from(filtersContainer.children).forEach(child => {
                child.classList.remove('bg-purple-700')
                child.classList.add('bg-gray-700')
            })
            btn.classList.remove('bg-gray-700')
            btn.classList.add('bg-purple-700')
        })
        filtersContainer.appendChild(btn)
    })
    filtersContainer.firstChild.classList.add('bg-purple-700')
}

startCameraBtn.addEventListener('click', startCamera)
captureBtn.addEventListener('click', capturePhoto)
retakeBtn.addEventListener('click', retakePhoto)
downloadBtn.addEventListener('click', downloadPhoto)
shareBtn.addEventListener('click', sharePhoto)
uploadInput.addEventListener('change', handleUpload)

brightnessInput.addEventListener('input', e => updateBrightness(e.target.value))
contrastInput.addEventListener('input', e => updateContrast(e.target.value))

createStickerButtons()
createFilterButtons()
updateBrightness(brightness)
updateContrast(contrast)
