import styles from "./html-blocks.uniq.css"

const { h, Fragment, UI, wirec, api, toast, Lod, useState, useEffect, useRef } = palacify;

palacify.page("html-blocks", function HtmlBlocks() {
    const [blocks, setBlocks] = useState(null);
    const [saving, setSaving] = useState(false);
    const [errors, setErrors] = useState({});
    const [delId, setDelId] = useState(false);
    const [deleting, setDeleting] = useState(false);
    const [state, setState] = useState({ mode: "list" });

    const wrapper = useRef();

    useEffect(() => {
        api.get(["html-blocks", "list"])
            .then(({ data: { status, payload } }) => {
                if (status === 200) {
                    setBlocks(payload);
                }
            })
            .catch((e) => {

            });
    }, []);

    const isEdit = state.mode === "edit";

    const onSave = () => {
        if (saving) {
            return;
        }

        setSaving(true);
        setErrors({});

        const payload = {};
        const names = ["name", "desc", "html"];

        Array.from(wrapper.current.querySelectorAll("[name]")).forEach((input) => {
            const { name, value } = input;

            if (name && names.indexOf(name) !== -1) {
                payload[name] = value.trim();
            }
        });

        payload.action = state.mode;
        payload.id = state?.block?._id || "";

        api.post(["html-blocks", "add-update"], payload)
            .then(({ data: { status, payload } }) => {
                if (status === 200) {
                    if (!isEdit) {
                        setBlocks([{ ...payload }, ...blocks]);
                        setState({ mode: "edit", block: payload });
                        toast.success("Added successfully");
                    }

                    if (isEdit) {
                        setBlocks(blocks.map((block) => {
                            if (block._id === state.block._id) {
                                return { ...payload };
                            }

                            return block;
                        }))

                        toast.success("Updated successfully");
                    }

                    return;
                }

                if (status === 422) {
                    return setErrors(payload);
                }

                toast.error("Failed to save");
            })
            .catch((e) => {
                toast.error("Failed to save");
            })
            .finally(() => {
                setSaving(false);
            })
    }

    const onDelete = () => {
        setDeleting(true);

        api.post(["html-blocks", "delete"], { id: delId })
            .then(({ data: { status, payload } }) => {
                if (status === 200) {
                    setBlocks(blocks.filter((block) => block._id !== delId));
                    setDelId(false);
                    return toast.success("Block deleted");
                }

                toast.error("Failed to delete");
            })
            .catch((e) => {
                toast.error("Failed to delete");
            })
            .finally(() => {
                setDeleting(false);
            });
    }

    return (
        <>
            {!!delId && (
                <UI.Confirm
                    progress={deleting}
                    onConfirm={onDelete}
                    onCancel={() => setDelId(false)}
                />
            )}

            <div className={styles.layout} ref={wrapper}>
                <div className={`${styles.bar} d5acflex d5gap10`}>
                    <i className="dashicons dashicons-screenoptions d5csec"></i><strong>Palacify HTML Blocks</strong>
                    &nbsp;&nbsp;
                    {state.mode === "list" && (<button className="d5btn sm" onClick={() => setState({ mode: "add" })}>+ Add</button>)}
                </div>

                <div className={styles.left}>
                    {blocks === null && (
                        <div className="d5fc">
                            <Lod size="28px" />
                        </div>
                    )}

                    {blocks !== null && blocks.length === 0 && state.mode === "list" && (
                        <div className="d5fc d5fdcol">
                            <div className="d5dim" style={{ fontSize: "20px" }}>No blocks.</div>
                            <div className="d5mt20">
                                <button onClick={() => setState({ mode: "add" })} style={{ paddingLeft: "10px", paddingRight: "14px" }} className="d5btn sm d5acflex"><i className="dashicons dashicons-plus-alt2 d5f14"></i>Add</button>
                            </div>
                        </div>
                    )}

                    {blocks !== null && blocks.length > 0 && state.mode === "list" && (
                        <div className={styles.blocks}>
                            {blocks.map((block, i) => {
                                const sn = (i + 1) < 10 ? `0${i + 1}` : i + 1;

                                return (
                                    <div key={block._id} className="d5pd1h d5flex d5gap10">
                                        <div className="d5f18 d5cpri">{sn}.</div>
                                        <div className="d5grow">
                                            <div className="d5f18">{block.name}</div>
                                            {!!block.desc && (<div className="d5dim d5mt6">{block.desc}</div>)}
                                            <span className={`${styles.shortcode} d5r4 d5csec`}>[palacify-html-block id="{block._id}"]</span>
                                        </div>
                                        <div className={`d5acflex d5gap6 ${styles.actions}`}>
                                            <i className="dashicons dashicons-edit d5clk dcsec" onClick={() => setState({ mode: "edit", block })}></i>
                                            <span className="d5dim">|</span>
                                            <i className="dashicons dashicons-trash d5clk d5cred" onClick={() => setDelId(block._id)}></i>
                                        </div>
                                    </div>
                                )
                            })}
                        </div>
                    )}

                    {state.mode !== "list" && (
                        <div className="d5pd1h">
                            <div className="d5f20">
                                {isEdit ? "Edit" : "Add"} block
                            </div>

                            <div className="d5mt14">
                                <div>Name<span className="d5cred">*</span></div>
                                <input type="text" name="name" disabled={saving} className="d5fw" autoComplete="off" defaultValue={state?.block?.name || ""} />
                                {!!errors.name && (<div className="d5ferr">{errors.name}</div>)}

                                <UI.Input />
                            </div>

                            <div className="d5mt14">
                                <div>Description</div>
                                <textarea className="d5fw" name="desc" disabled={saving} rows={4} defaultValue={state?.block?.desc || ""} />
                            </div>

                            <div className="d5mt14 d5acflex d5gap10">
                                <button className="d5btn sec" disabled={saving} onClick={() => { setErrors({}); setState({ mode: "list" }); }}>⇠ Back</button>

                                <div className="d5grow">
                                    <button className="d5btn" disabled={saving} onClick={onSave}>{saving ? <Lod /> : ""} Save</button>
                                </div>
                                <div className="d5tr">
                                    Write your HTML on the right side ⇢
                                </div>
                            </div>
                        </div>
                    )}
                </div>

                <div className={styles.right}>
                    {state.mode !== "list" && (
                        <textarea className={styles.html} disabled={saving} name="html" placeholder="HTML goes here.." defaultValue={state?.block?.html || ""} />
                    )}

                    {state.mode === "list" && (
                        <div className="d5fc d5dim">
                            <WaitIcon />
                        </div>
                    )}
                </div>
            </div>
        </>
    )
});

const WaitIcon = () => {
    return (
        <svg
            fill="#426191"
            width="64px"
            height="64px"
            viewBox="0 0 16 16"
            xmlns="http://www.w3.org/2000/svg"
        >
            <path
                d="M-13.5,3.5V8a.5.5,0,0,1-.5.5.5.5,0,0,1-.5-.5V3.5A.5.5,0,0,1-14,3,.5.5,0,0,1-13.5,3.5ZM-13,.55a.5.5,0,0,0-.465-.532Q-13.731,0-14,0a8.009,8.009,0,0,0-8,8,8.009,8.009,0,0,0,8,8q.268,0,.534-.018A.5.5,0,0,0-13,15.45a.507.507,0,0,0-.533-.466c-.154.011-.309.016-.466.016a7.008,7.008,0,0,1-7-7,7.008,7.008,0,0,1,7-7c.157,0,.312,0,.466.016h.034A.5.5,0,0,0-13,.55Zm2.126,13.716a7.165,7.165,0,0,1-.842.354.5.5,0,0,0-.31.635.5.5,0,0,0,.473.337.516.516,0,0,0,.163-.027,8.087,8.087,0,0,0,.962-.4.5.5,0,0,0,.224-.671A.5.5,0,0,0-10.875,14.266ZM-8.738,3.383a.5.5,0,0,0,.376.171.5.5,0,0,0,.33-.124.5.5,0,0,0,.046-.706,7.93,7.93,0,0,0-.739-.739.5.5,0,0,0-.7.047.5.5,0,0,0,.046.7A6.91,6.91,0,0,1-8.738,3.383Zm-3.005-2.011a6.892,6.892,0,0,1,.845.351.5.5,0,0,0,.221.051.5.5,0,0,0,.448-.278.5.5,0,0,0-.227-.67,8.041,8.041,0,0,0-.964-.4.5.5,0,0,0-.635.312A.5.5,0,0,0-11.743,1.372ZM-6.73,9.919a.5.5,0,0,0-.633.314,7.106,7.106,0,0,1-.348.845.5.5,0,0,0,.229.67.5.5,0,0,0,.219.05.5.5,0,0,0,.45-.279,8.145,8.145,0,0,0,.4-.967A.5.5,0,0,0-6.73,9.919ZM-8.721,12.6a7.043,7.043,0,0,1-.644.649.5.5,0,0,0-.042.706.5.5,0,0,0,.374.168.493.493,0,0,0,.331-.126,7.9,7.9,0,0,0,.735-.74.5.5,0,0,0-.048-.706A.5.5,0,0,0-8.721,12.6Zm2.7-5.135A.5.5,0,0,0-6.551,7a.5.5,0,0,0-.465.532C-7.005,7.685-7,7.842-7,8s0,.3-.014.442a.5.5,0,0,0,.466.532h.033a.5.5,0,0,0,.5-.467C-6.005,8.34-6,8.17-6,8S-6.006,7.639-6.018,7.462Zm-1.354-1.72a.5.5,0,0,0,.474.339.508.508,0,0,0,.161-.027.5.5,0,0,0,.312-.635,8.056,8.056,0,0,0-.4-.964.5.5,0,0,0-.67-.226.5.5,0,0,0-.226.669A6.939,6.939,0,0,1-7.372,5.742Z"
                transform="translate(22)"
            />
        </svg>
    );
};

setTimeout(wirec.put, 350, "render", "html-blocks");
