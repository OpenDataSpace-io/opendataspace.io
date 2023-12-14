import React, { ChangeEvent } from 'react';
import AWS from 'aws-sdk';

interface S3UploadFieldProps {
    onChange: (location: string) => void;
}

const S3UploadField: React.FC<S3UploadFieldProps> = (props) => {
    const handleFileChange = async (event: ChangeEvent<HTMLInputElement>) => {
        const file = event.target.files![0];

        AWS.config.update({
            accessKeyId: process.env.AWS_ACCESS_KEY_ID,
            secretAccessKey: process.env.AWS_SECRET_ACCESS_KEY,
            region: process.env.AWS_REGION,
        });

        const s3 = new AWS.S3();
        const params = {
            Bucket: process.env.AWS_BUCKET_NAME,
            Key: file.name,
            Body: file,
            ACL: 'public-read',
        };

        s3.upload(params, function (err, data) {
            if (err) {
                console.log(err);
            } else {
                console.log(`File uploaded successfully at ${data.Location}`);
                // Update the form data with the URL of the uploaded file
                props.onChange(data.Location);
            }
        });
    };

    return <input type="file" onChange={handleFileChange} />;
};

export default S3UploadField;