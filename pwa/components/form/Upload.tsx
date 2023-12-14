import { useState } from 'react';
import AWS from 'aws-sdk';

export default function Upload() {
    const [selectedFile, setSelectedFile] = useState();

    const handleFileChange = (event) => {
        setSelectedFile(event.target.files[0]);
    };

    const handleUpload = async () => {
        AWS.config.update({
            accessKeyId: process.env.AWS_ACCESS_KEY_ID,
            secretAccessKey: process.env.AWS_SECRET_ACCESS_KEY,
            region: process.env.AWS_REGION,
            endpoint: 'https://sos-ch-dk-2.exo.io',
        });

        const s3 = new AWS.S3();
        const params = {
            Bucket: process.env.AWS_BUCKET_NAME,
            Key: selectedFile.name,
            Body: selectedFile,
            ACL: 'public-read',
        };

        s3.upload(params, function (err, data) {
            if (err) {
                console.log(err);
            } else {
                console.log(`File uploaded successfully at ${data.Location}`);
            }
        });
    };

    return (
        <div>
            <input type="file" onChange={handleFileChange} />
            <button onClick={handleUpload}>Upload</button>
        </div>
    );
}