import { type NextPage } from "next";
import Head from "next/head";
import Image from "next/image";
import Link from "next/link";
import { useEffect, useState, useCallback, ComponentType } from "react";
import { useMutation } from "react-query";
import Typography from "@mui/material/Typography";
import Breadcrumbs from "@mui/material/Breadcrumbs";
import FavoriteBorderIcon from "@mui/icons-material/FavoriteBorder";
import FavoriteIcon from "@mui/icons-material/Favorite";
import { Container, Grid, Button } from '@mui/material';
import { signIn, useSession } from "next-auth/react";

import { type Thing } from "@/types/Thing";
import { useMercure } from "@/utils/mercure";
import { useThing } from "@/utils/thing";
//import { fetch, type FetchError, type FetchResponse } from "@/utils/dataAccess";
import { type PagedCollection } from "@/types/collection";
import { Loading } from "@/components/common/Loading";
import ShowProperties from "./ShowProperties";
import Editors from '@/components/form/Editors';

import { ErrorSchema, RJSFSchema, RJSFValidationError, UiSchema, ValidatorType } from '@rjsf/utils';;
import validator from '@rjsf/validator-ajv8';
import { FormProps, IChangeEvent, withTheme } from '@rjsf/core';
import Form from '@rjsf/mui';

interface Props {
    data: Thing;
    hubURL: string | null;
    page: number;
}

// https://rjsf-team.github.io/react-jsonschema-form/docs/

export const Edit: NextPage<Props> = ({ data, hubURL, page }) => {
    const { data: session, status } = useSession();
    const item = useMercure(data, hubURL);
    const [schema, setSchema] = useState({});
    const [uiSchema, setUiSchema] = useState({});
    const [formData, setFormData] = useState({});
    const [selectedForm, setSelectedForm] = useState('');
    const [formList, setFormList] = useState([]);
    const [extraErrors, setExtraErrors] = useState<ErrorSchema | undefined>();
    const [shareURL, setShareURL] = useState<string | null>(null);
    const [isGridVisible, setGridVisible] = useState(false); // Changed initial value to false
    const [FormComponent, setFormComponent] = useState<ComponentType<FormProps>>(withTheme({}));

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
                setFormData(item);

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
                setFormData(item);
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

    const handleFormSelect = (e : any) => {
        setSelectedForm(e.target.value);
    };

    const handleFormDataChange = (e : any) => {
        try {
            const newFormData = JSON.parse(e.target.value);
            setFormData(newFormData);
        } catch (error) {
            console.error('Invalid JSON');
        }
    };

    const onFormDataChange = useCallback(
        ({ formData }: IChangeEvent, id?: string) => {
            if (id) {
                console.log('Field changed, id: ', id);
            }

            setFormData(formData);
            setShareURL(null);
        },
        [setFormData, setShareURL]
    );

    const handleSubmit = async (data: any) => {
        // Handle form submission here
        console.log(data);
        console.log(data["formData"]);
        console.log(item);

        const id = item['@id'];
        console.log(id);

        try {
            const token = session.accessToken; // Get the authentication token from the session
            const response = await fetch(`${id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${token}` // Include the authentication token in the request headers
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

    const excludeFields: Array<string | never[]> = [
        "@context",
        "@id",
        //"@type",
        "id",
    ];

    // Function to check if a field has the format "data-url"
    const isDataUrlField = (field: any) => {
        return field.format === "data-url";
    };

    if (status === "loading" || !schema || !uiSchema) {
        return <Loading />;
    } else if (session?.error || session === null) {
        signIn();
    } else if (item) {
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
                                            onChange={onFormDataChange}
                                            onSubmit={handleSubmit}
                                            /*fields={{
                                                // Override the default field component for fields with format "data-url"
                                                'data-url': FileUpload,
                                                'geo': GeoWidget,
                                            }}*/
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
                                    {formList.map(form => (
                                        <option key={form.id} value={form.id}>{form.name}</option>
                                    ))}
                                    <option value="dynamic">TODO: Dynamic Form by Fields*</option>
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
    }
};