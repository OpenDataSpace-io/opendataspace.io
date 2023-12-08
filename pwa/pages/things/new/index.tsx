import React, { useState, useEffect } from 'react';
import { RJSFSchema } from '@rjsf/utils';
import validator from '@rjsf/validator-ajv8';
import Form from '@rjsf/mui';

const NewThingForm = () => {
    const [schema, setSchema] = useState({});
    const [uiSchema, setUiSchema] = useState({});
    const [formData, setFormData] = useState({});
    const [selectedForm, setSelectedForm] = useState('');
    const [formList, setFormList] = useState([]);

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
        <div className="min-w-[270px] max-w-[300px] w-full mr-10 text-center">
            <h1>New Thing Form</h1>
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
                    <h2>JSONSchema</h2>
                        <textarea
                            value={JSON.stringify(schema, null, 2)}
                            onChange={handleFormDataChange}
                        />
                        <h2>UISchema</h2>
                        <textarea
                            value={JSON.stringify(uiSchema, null, 2)}
                            onChange={handleFormDataChange}
                        />
                        <h2>formData</h2>
                        <textarea
                            value={JSON.stringify(formData, null, 2)}
                            onChange={handleFormDataChange}
                        />
                    </div>
                    <Form
                        schema={schema}
                        uiSchema={uiSchema}
                        formData={formData}
                        validator={validator}
                        onSubmit={handleSubmit}
                    />
                </>
            )}
        </div>
    );
};

export default NewThingForm;
