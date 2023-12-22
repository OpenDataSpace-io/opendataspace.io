import React, { useState, useEffect } from 'react';
import { ErrorSchema, RJSFSchema, RJSFValidationError, UiSchema, ValidatorType } from '@rjsf/utils';
import validator from '@rjsf/validator-ajv8';
import Form from '@rjsf/mui';
import Head from "next/head";
import Editors from '@/components/form/Editors';
import { Container, Grid, Button } from '@mui/material';
import { signIn, useSession } from "next-auth/react";

interface Session {
    accessToken: string;
    error: string;
}

const METHOD = 'POST';
const CONTENT_TYPE_HEADER = 'Content-Type';
const AUTHORIZATION_HEADER = 'Authorization';
const BEARER_PREFIX = 'Bearer';
const APPLICATION_JSON = 'application/json';

const NewThingForm = () => {
    const [schema, setSchema] = useState({});
    const [uiSchema, setUiSchema] = useState({});
    const [formData, setFormData] = useState({});
    const [selectedForm, setSelectedForm] = useState('');
    const [formList, setFormList] = useState([]);
    const [extraErrors, setExtraErrors] = useState<ErrorSchema | undefined>();
    const [shareURL, setShareURL] = useState<string | null>(null);
    const [isGridVisible, setGridVisible] = useState(true);
    const { data: session = { accessToken: '', error: '' }, status } = useSession() || {};

    const handleButtonClick = () => {
        setGridVisible(!isGridVisible);
    };
    
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
                setFormData(defaultForm[0].formData);

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
                setFormData(data.formData);
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

    const handleFormSelect = (e: React.ChangeEvent<HTMLSelectElement>) => {
        setSelectedForm(e.target.value);
    };

    const handleFormDataChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        try {
            const newFormData = JSON.parse(e.target.value);
            setFormData(newFormData);
        } catch (error) {
            console.error('Invalid JSON');
        }
    };

    const handleSubmit = async (data: any) => {
        // Handle form submission here
        console.log(data);
        console.log(data["formData"]);

        try {
            //if (!session) return;
            //@ts-ignore
            const token = session.accessToken; // Get the authentication token from the session
            const response = await fetch('/things', {
                method: METHOD,
                headers: {
                    [CONTENT_TYPE_HEADER]: APPLICATION_JSON,
                    [AUTHORIZATION_HEADER]: `${BEARER_PREFIX} ${token}` // Include the authentication token in the request headers
                },
                body: JSON.stringify(data["formData"]) // Set the request body as data["formData"]
            });

            if (response.ok) {
                // Handle successful response
                console.log('Thing created successfully');
            } else {
                // Handle error response
                console.error('Error creating thing:', response.statusText);
            }
        } catch (error) {
            console.error('Error creating thing:', error);
        }
    };

    return (
        <>
            <Head>
                <title>New Thing</title>
            </Head>
            <Container maxWidth="xl">
            <Button onClick={handleButtonClick}>
                {isGridVisible ? 'Hide Expert Mode' : 'Show Expert Mode'}
            </Button>
                <Grid container spacing={2}>
                    <Grid item xs={isGridVisible ? 6 : 12} md={isGridVisible ? 8 : 12}>
                        {selectedForm && (
                        <>
                            <div>
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
                    </Grid>
                    {isGridVisible && (
                        <Grid item xs={6} md={4}>
                            <h2>Select Form</h2>
                            <select value={selectedForm} onChange={handleFormSelect}>
                                <option value="">Select a form</option>
                                {formList.map((form: { id: string, name: string }) => (
                                    <option key={form.id} value={form.id}>{form.name}</option>
                                ))}
                            </select>
                            {selectedForm && (
                                <>
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
                                </>
                            )}
                        </Grid>
                    )}
                </Grid>
            </Container>
        </>
    );
};

export default NewThingForm;