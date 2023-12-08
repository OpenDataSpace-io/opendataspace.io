import React, { useState, useEffect } from 'react';
import { ErrorSchema, RJSFSchema, RJSFValidationError, UiSchema, ValidatorType } from '@rjsf/utils';
import validator from '@rjsf/validator-ajv8';
import Form from '@rjsf/mui';
import Head from "next/head";
import Editors from '@/components/form/Editors';
import { Container } from '@mui/material';

const NewThingForm = () => {
    const [schema, setSchema] = useState({});
    const [uiSchema, setUiSchema] = useState({});
    const [formData, setFormData] = useState({});
    const [selectedForm, setSelectedForm] = useState('');
    const [formList, setFormList] = useState([]);
    const [extraErrors, setExtraErrors] = useState<ErrorSchema | undefined>();
    const [shareURL, setShareURL] = useState<string | null>(null);
    
    // Fetch the list of available forms from the API
    useEffect(() => {
        const fetchFormList = async () => {
            try {
                const response = await fetch('/forms.json');
                const data = await response.json();
                console.log("set form list");
                setFormList(data);

                const defaultForm = data.filter((element: { code: string }) =>
                    element.code === "place"
                );

                console.log("defaultForm");
                console.log(defaultForm);
                setSelectedForm(defaultForm[0].id)
                setSchema(defaultForm[0].JSONSchema);
                setUiSchema(defaultForm[0].UISchema);

            } catch (error) {
                console.error('Error fetching form list:', error);
            }
        };

        fetchFormList();
    }, []);

    useEffect(() => {
        // Fetch the JSONSchema and UISchema from the API based on the selected form
        const fetchForm = async () => {
            try {
                const response = await fetch(`/forms/${selectedForm}.json`);
                const data = await response.json();
                setSchema(data.JSONSchema);
                setUiSchema(data.UISchema);
                //setFormData(data.formData);
            } catch (error) {
                console.error('Error fetching form:', error);
            }
        };

        if (selectedForm) {
            fetchForm();
        } else {
            console.log("no form selected");
        }
    }, [selectedForm]);

    const handleFormSelect = (e) => {
        setSelectedForm(e.target.value);
    };

    const handleFormDataChange = (e) => {
        try {
            const newFormData = JSON.parse(e.target.value);
            setFormData(newFormData);
        } catch (error) {
            console.error('Invalid JSON');
        }
    };

    const handleSubmit = (formData) => {
        // Handle form submission here
        console.log(formData);
    };



    return (
        <>
            <Head>
                <title>New Thing</title>
            </Head>
            <Container fixed>
            <div className="container mx-auto max-w-7xl items-center justify-between p-6 lg:px-8">
            <div>
                <h2>Select Form</h2>
                <select value={selectedForm} onChange={handleFormSelect}>
                    <option value="">Select a form</option>
                    {formList.map(form => (
                        <option key={form.id} value={form.id}>{form.name}</option>
                    ))}
                </select>
            </div>
            {selectedForm && (
                <>
                    <div>
                     <Editors
                        formData={formData}
                        setFormData={setFormData}
                        schema={schema}
                        setSchema={setSchema}
                        uiSchema={uiSchema}
                        setUiSchema={setUiSchema}
                        extraErrors={extraErrors}
                        setExtraErrors={setExtraErrors}
                        setShareURL={setShareURL}
                    />
                    <Form
                        schema={schema}
                        uiSchema={uiSchema}
                        formData={formData}
                        validator={validator}
                        onSubmit={handleSubmit}
                    />
                    </div>
                </>
            )}
            </div>
            </Container>
        </>
    );
};

export default NewThingForm;
