export type Model = {
id: string;
name: string;
description: string | null;
};
export type ModelsResponse = {
data: Array<Model>;
};
