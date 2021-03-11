'use strict';

class RekognitionResult {

    /**
     * コンストラクタ
     */
    constructor(detectResult) {
        this.detectResult = detectResult;
        this.detectType = Object.keys(detectResult)[0];
    }

    /**
     * Rekognitionの結果からBoundingBox配列だけを抽出します。
     * @returns array BoundingBoxの配列
     */
    getBoundingBox() {
        const typeRoot = this.detectResult[this.detectType];

        if (this.detectType === "FaceDetails") {
            return typeRoot.map((face) => face.BoundingBox);
        } else if (this.detectType == "Labels") {
            return typeRoot
                .map((label) => {
                    return label.Instances.map((ins) => ins.BoundingBox);
                })
                .flat();
        } else if (this.detectType == "TextDetections") {
            return typeRoot
                .filter((text, i, self) => {
                    const bb = text.Geometry.BoundingBox;

                    const same = self.find(t => {
                        const b = t.Geometry.BoundingBox;
                        return Number(t.Id) < Number(text.Id)
                            && t.DetectedText === text.DetectedText
                            && b.Width === bb.Width
                            && b.Height === bb.Height
                            && b.Left === bb.Left
                            && b.Top === bb.Top;
                    });

                    return typeof same === 'undefined';
                })
                .map((text) => {
                    return text.Geometry.BoundingBox;
                });
        }
    }

    /**
     * BoundingBoxから画像内の枠を作成します。
     *
     * @param {Object} canvasObj
     * @param {Object} imageObj
     * @param {Object} imageContext
     * @param {Number} imageWidth
     * @param {Number} imageHight
     * @returns
     */
    createBoundingBox({ canvasObj = {}, imageContext = {}, imageObj, imageWidth = null, imageHeight = null }) {
        const height = imageHeight ?? imageObj.naturalHeight;
        const width = imageWidth ?? imageObj.naturalWidth;

        return this.getBoundingBox().map((bb, i) => {
            const convertBoxsize = (len) => len * 100 + "%";
            const box = {
                Width: convertBoxsize(bb.Width),
                Height: convertBoxsize(bb.Height),
                Left: convertBoxsize(bb.Left),
                Top: convertBoxsize(bb.Top),
            };

            const show = () => {
                canvasObj.width = bb.Width * width;
                canvasObj.height = bb.Height * height;

                imageContext.drawImage(
                    imageObj,
                    bb.Left * width,
                    bb.Top * height,
                    bb.Width * width,
                    bb.Height * height,
                    0,
                    0,
                    bb.Width * width,
                    bb.Height * height
                );
            };

            const clear = () => {
                imageContext.clearRect(
                    0,
                    0,
                    bb.Width * width,
                    bb.Height * height
                );
            };

            return { i, box, show, clear };
        });
    }
}

export default RekognitionResult;
